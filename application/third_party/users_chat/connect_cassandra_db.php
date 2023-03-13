<?php
    /**
     * This file is used to connect cassandra database and perform curd operations
     */
    class connect_cassandra_db {

        private $cluster; 
        private $keyspace = CASSANDRA_DB_KEYSPACE;
        private $session;
        public function __construct() {
        }
        /**
         * This method is used to connect with cassandra db
        */
        private function connect_db() {
            try {
                
                $this->cluster = Cassandra::cluster()
                                ->withDefaultPageSize(null)
                                ->withContactPoints(CASSANDRA_DB_HOST)
                                ->WithCredentials(CASSANDRA_DB_USER, CASSANDRA_DB_PASSWORD)
                                ->WithPort(CASSANDRA_DB_PORT)
                                ->build();
                $this->session = $this->cluster->connect($this->keyspace); // create session, optionally scoped to a keyspace
            } catch(\Cassandra\Exception\RuntimeException $e) {
                echo 'connection fail';
            }           
        }
        /**
         * This method is used to disconnect from cassanadra db
        */
        private function disconnect_db() {
            $this->session->close();
        }
        /**
         * This method is used to fetach data from table specified in argument
        */
        public function select($table = '', $column = []) {
            $this->connect_db();
            
            $sql = 'SELECT ';
            if(!empty($column) && is_array($column)) {
                $sql .= implode(',', $column);
            } else {
                $sql .= '*';
            }
            $sql .= ' FROM '.$table;
            $statement = new Cassandra\SimpleStatement(       // also supports prepared and batch statements
                $sql
            );
            
            $future    = $this->session->executeAsync($statement);  // fully asynchronous and easy parallel execution
            $result    = $future->get(); 
            $this->disconnect_db();
            return $result;
        }
        /**
         * This method is used to insert data into table which specified into argument
        */
        public function insert($table = '', $data = []) {
            $this->connect_db();
            $sql = 'INSERT INTO '.$table.' ('.implode(', ', array_keys($data)).') VALUES ('.implode(',', $data).')';
            try {
                $statement = new Cassandra\SimpleStatement(       // also supports prepared and batch statements
                    $sql
                );
                
                $future    = $this->session->executeAsync($statement); 
                $this->disconnect_db();
                return true;
            } catch(Cassandra\Exception $e) {
                $this->disconnect_db();
                return false;
            }   
        }
        /**
         * This method is used to get chat between PO and SP for specific project
         * @param Boolean $flag this variable is used when receiver is disconnected from internet and socket and after sometime he reconnect then we need to based on this variable and latest message timestamp send all lost data
        */
        public function get_users_conversations_on_project($sender_id, $receiver_id, $project_id, $limit, $timestamp = '', $order = 'DESC', $flag = false) {
            $sql = '';
            $this->connect_db();
            $sql .= 'SELECT * ';
            $sql .= 'FROM users_chats_messages ';
            $sql .= 'WHERE sender_id IN ('.$sender_id.','.$receiver_id.') AND receiver_id IN ('.$sender_id.','.$receiver_id.') ';
            $sql .= 'AND project_id='.$project_id;
            if(!empty($timestamp)) {
                if($flag) {
                    $sql .= ' AND message_sent_time >'."'".gmdate('Y-m-d H:i:s+0000',$timestamp)."'";
                } else {
                    $sql .= ' AND message_sent_time <'."'".gmdate('Y-m-d H:i:s+0000',$timestamp)."'";
                }
            }
            $sql .= ' ORDER BY message_sent_time '.$order;
            if(!empty($limit)) {
                $sql .= ' LIMIT '.$limit;
            }
            $sql .= ' ALLOW FILTERING';
            $statement = new Cassandra\SimpleStatement(       // also supports prepared and batch statements
                $sql
            );
            $future    = $this->session->executeAsync($statement);  // fully asynchronous and easy parallel execution
            $result    = $future->get(); 
            $this->disconnect_db();
            return $result;
        }
        /**
         * This method is used to update chat message status from un-read to read for specified sender and receiver
        */
        public function update_chat_messages_read_unread_status($sender_id, $receiver_id, $project_id) {
            $this->connect_db();
            $sql = '';
            $sql .= 'SELECT * ';
            $sql .= 'FROM users_chats_messages ';
            $sql .= 'WHERE receiver_id ='.$sender_id;
            $sql .= 'AND sender_id='.$receiver_id;
            $sql .= ' AND project_id='.$project_id;
            $sql .= ' AND is_read = false';
            $sql .= ' ALLOW FILTERING';
            $statement = new Cassandra\SimpleStatement(       // also supports prepared and batch statements
                $sql
            );
            $future    = $this->session->executeAsync($statement);  // fully asynchronous and easy parallel execution
            $result    = $future->get(); 
            foreach($result as $val) {
                $timepstamp = (array)$val['message_sent_time'];
                $update_sql = 'UPDATE users_chats_messages SET is_read = true WHERE message_sent_time = '."'".gmdate('Y-m-d h:i:s+0000', $timepstamp['seconds'])."'".' AND sender_id = '.$val['sender_id'].' AND receiver_id ='.$val['receiver_id'];
                $statement = new Cassandra\SimpleStatement(       // also supports prepared and batch statements
                    $update_sql
                ); 
                $future    = $this->session->executeAsync($statement); 
            }
            $this->disconnect_db();
            return true;
        }
        /**
         * This method is used to get unread messages count for specified sender and receiver
        */
        public function get_unread_chat_messages_count($receiver_id, $project_id, $sender_id = '') {
            $this->connect_db();
            $sql = '';
            $sql .= 'SELECT * ';
            $sql .= 'FROM users_chats_messages ';
            $sql .= 'WHERE receiver_id = '.$receiver_id;
            if(!empty($sender_id)) {
                $sql .= ' AND sender_id='.$sender_id;
            }
            $sql .= ' AND project_id='.$project_id;
            $sql .= ' AND is_read = false';
            $sql .= ' ALLOW FILTERING';
            $statement = new Cassandra\SimpleStatement($sql);
            $future    = $this->session->executeAsync($statement);  // fully asynchronous and easy parallel execution
            $result    = $future->get(); 
            $this->disconnect_db();
            return $result->count();
        }
        // This function is used to select row of cassandra db for pass attachment name -> /application/modules/chat/controllers/chat.php
        public function get_chat_attachments_from_name ($name) {
            $this->connect_db();
            $sql = '';
            $sql .= 'SELECT *';
            $sql .= 'FROM users_chats_messages ';
            $sql .= 'WHERE chat_attachments CONTAINS '.$name;
            $sql .= ' ALLOW FILTERING';
            $statement = new Cassandra\SimpleStatement($sql);
            $future    = $this->session->executeAsync($statement);  // fully asynchronous and easy parallel execution
            $result    = $future->get(); 
            $this->disconnect_db();
            $res = [];
            if($result->count() > 0) {
                foreach($result as $key => $val) {
                    $res = $val;
                }
            } 
            return $res;
        }
        /**
         * This method is used to remove attachment from db if attachments will not exist on disk
         */
        public function delete_chat_attachments_by_name($name, $data) {
            $timepstamp = (array)$data['message_sent_time'];
            if($name == 'null' && $data['chat_message_text'] == '') {
                $delete_sql = "DELETE FROM users_chats_messages";
                $delete_sql .= ' WHERE message_sent_time = '."'".gmdate('Y-m-d H:i:s+0000', $timepstamp['seconds'])."'".' AND sender_id = '.$data['sender_id'].' AND receiver_id ='.$data['receiver_id'];
                $statement = new Cassandra\SimpleStatement($delete_sql); 
                $future    = $this->session->executeAsync($statement);
            } else {
                $update_sql = "UPDATE users_chats_messages SET chat_attachments =". $name; 
                $update_sql .= ' WHERE message_sent_time = '."'".gmdate('Y-m-d H:i:s+0000', $timepstamp['seconds'])."'".' AND sender_id = '.$data['sender_id'].' AND receiver_id ='.$data['receiver_id'];
                $statement = new Cassandra\SimpleStatement($update_sql); 
                $future    = $this->session->executeAsync($statement);
            }
             
        }
    }

    // $conn = new connect_cassandra_db();

    // $data = [
    //     'id' => 'uuid()',
    //     'sender_id' => 1,
    //     'receiver_id' => 2,
    //     'chat_message_text' => "'Hello, what is going on?'",
    //     'project_id' => "6568798790",
    //     'message_sent_time' => 'toTimeStamp(now())',
    //     'chat_attachments' => null,
    //     'is_general_chat' => false
    // ];
    // $conn->insert('users_chats_messages', $data);

    // $result = $conn->select('users_chats_messages', '*');
    // // echo $result->count();
    // foreach ($result as $row) { 
    //     $id = (array)$row['id'];
    //     echo $id['uuid'].' '.$row['chat_message_text'];
    // }
?>