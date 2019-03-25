<?php
  // Imports
  require 'logger.php';

	// Create database instance
   class smsDB extends SQLite3 {
   		private
   			$db,
        $log;

      // Create DB instance
   		public function __construct() {
   			$this->db = new SQLite3('gobeba_sms.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $this->log = new Logger("database_logs.txt");
        $this->createTables();
   		}

      public function createTables() {
        // Bootstrap tables
        $this->db->query('CREATE TABLE IF NOT EXISTS "sms" (
           "id" INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
           "statusCode" VARCHAR,
           "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
           "number" VARCHAR,
           "status" VARCHAR,
           "cost" VARCHAR,
           "sessionId" VARCHAR,
           "statusCodeDescription" VARCHAR,
           "statusDescription" VARCHAR,
           "failureReason" VARCHAR,
           "networkCode" VARCHAR,
           "retryCount" VARCHAR
         )');
      }

      // Generic insert function 
      public function insert($table, $data) {
        $fields = $values = array();

        // Get fields and their values
        // Assumes no foreign fields exists in data
        // Escapes strings to prevent injection attacks
        foreach( array_keys($data) as $key ) {
            $fields[] = "`$key`";
            $values[] = "'" . SQLite3::escapeString($data[$key]) . "'";
        }

        // Comma separate the values
        $fields = implode(",", $fields);
        $values = implode(",", $values);

        // Execute insert query
        $insert = "INSERT INTO `$table` ($fields) VALUES ($values)";

        try {
          $query = $this->db->prepare($insert);
          $query->execute();          
        }
        catch (Exception $e) {
            $error = array(
              "error" => $e->getMessage()
            ); 
            // Logs for debug
            $this->log->insert(json_encode($error));
        }
      }

      // Update SMS table from callback
   		public function update($id, $data) {
        $status = $data['status'];
        $failureReason = $data['failureReason'];
        $description = $data['description'];
        $retryCount = $data['retryCount'];
        $networkCode = $data['networkCode'];

        $this->log->insert(json_encode($data));

        $update = "UPDATE sms SET
              status = '$status',
              failureReason = '$failureReason',
              statusDescription = '$description',
              networkCode = '$networkCode',
              retryCount = '$retryCount'
          WHERE
              sessionId = '$id'";
        try {
          $query = $this->db->prepare($update);
          $this->db->enableExceptions(true);
          $query->execute();
        }
        catch (Exception $e) {
            $error = array(
              "error" => $e->getMessage()
            ); 
            // Logs for debug
            $this->log->insert(json_encode($error));
        }
   		}

      // Get message by ID
      public function get($id) {
        $get = "SELECT * FROM sms WHERE sessionId = '$id'";
        $query = $this->db->querySingle($get, true);
        return json_encode($query);
      }

   		public function close() {
   			$this->db->close();
   		}
   }

?>