<?
class DBconnect {
	
	//***** Declare Class Variables *****// 
   var $dbhost;  
   var $dbuser;  
   var $dbpswd;  
   var $dbname;  
   var $master;  
   var $ready;  
   
   
    //***** __CONSTRUCT(); *****// 
   function __construct() {
	   $this->ready="0";
   }
   
   
    //***** SETINFO(); *****//
   function setinfo($dbhost,$dbuser,$dbpswd,$dbname,$master) {
       $this->dbhost = $dbhost;
       $this->dbuser = $dbuser;
       $this->dbpswd = $dbpswd;
       $this->dbname = $dbname;
       $this->master = $master;
       $this->ready = "1";
   }
   
   
    //***** CHECKREADY(); *****// 
   function checkready(){
	   if($this->ready!="1"){
		echo "<h3>Error, Database info has not been provided for excecute of class DBconnect.</h3>
	   		  Please use function Setinfo(\$dbhost,\$dbuser,\$dbpswd,\$dbname,\$master); before calling any other function.";
	  	die("<strong><br><br>-Unable to continue-<br><br></strong>");
	   } else if($this->dbhost==''||$this->dbuser==''||$this->dbpswd==''||$this->dbname==''||$this->master==''||!$this->dbhost||!$this->dbuser||!$this->dbpswd||!$this->dbname||!$this->master){
		echo "<h3>Error, Database info for excecute of class DBconnect must have a value.</h3>
	   		  Please use function Setinfo(\$dbhost,\$dbuser,\$dbpswd,\$dbname,\$master); with valid content before calling any other function.";
	  	die("<strong><br><br>-Unable to continue-<br><br></strong>");		
		}
   }



    //***** DBCONNECTME(); *****// 
   function dbconnectme() {
	   $this->checkready();
	   $this->con = mysql_connect($this->dbhost,$this->dbuser,$this->dbpswd);
	   
	   if (!$this->con)
	     {
	     	die('Could not connect: ' . mysql_error());
	     }
	   if(!mysql_select_db($this->dbname, $this->con)){
	        die('Could not set DB: ' . mysql_error());
	     }
   }   
      
	  
	  
	  
    //***** INSERTDATABASE(); *****// 
   function insertdatabase($fields,$data,$table){
      $this->dbconnectme();

      $this->query="INSERT INTO ".$table;

      $this->firstfield=1;
      $this->firstdata=1;

      foreach($fields as $fields) 
	      {
	      	if($this->firstfield==1){
	      		$this->query=$this->query." (".$fields;
	      		$this->firstfield=2;
	      	} else {
	      	$this->query=$this->query.", ".$fields;
	      	}	
          }

      $this->query=$this->query.") VALUES ";

      foreach($data as $data) 
	      {
		      if($this->firstdata==1){
			      $this->query=$this->query." ('".$data;
			      $this->firstdata =2;
		      } else {
		      $this->query=$this->query."', '".$data;
		      }	
	      }

      $this->query=$this->query."')";

      if(mysql_query($this->query)){
	      $this->final=1;
      } else {
	      $this->final=0;
      }

      return $this->final;
   }

	  
	  
	  
	  
    //***** CHECKEXIST(); *****// READY
function checkexist($field,$data,$table)
{
	$this->dbconnectme();

	$this->query="SELECT * FROM ".$table." WHERE ".$field."='".$data."' LIMIT 1";
	
	
	$this->result = mysql_query($this->query);
$this->final=0;
while($this->row = mysql_fetch_array($this->result))
  {
	  $this->final=$this->final+1;
  }
  
return $this->final;

} 



    //***** READDATABASE(); *****//
function readdatabase($select,$where,$data,$table,$orderby="0",$groupby="0",$limit="0")
{
	 $this->dbconnectme();

	$this->query="SELECT "; 

if($select=="0"||$select==""){
	$this->query=$this->query." * "; 
} else {
	$this->query=$this->query." ".$select." "; 
}

	$this->query=$this->query." FROM ".$table." "; 

if($where[0]=="0"||$where==""||$where[0]==""){
} else {
	$this->query=$this->query." WHERE "; 
	$this->firststring=0;
	$this->quantity = count($where);
	$this->quantity=$this->quantity-1;
	for ($i=0;$i<=$this->quantity;$i++){
		if($this->firststring==0){
			$this->info=$where[$i]."='".$data[$i]."'";
			$this->firststring=1;
		} else {
		$this->info=$this->info." AND ".$where[$i]."='".$data[$i]."'";
		}
	}
	$this->query=$this->query.$this->info;
}

if($groupby=="0"||$groupby==""){
} else {
	$this->query=$this->query." GROUP BY ".$groupby; 
}
if($orderby=="0"||$orderby==""){
} else {
	$this->query=$this->query." ORDER BY ".$orderby; 
}
if($limit=="0"||$limit==""){
} else {
	$this->query=$this->query." LIMIT ".$limit; 
}

$this->newarray=mysql_query($this->query);
if(!$this->newarray){
	$this->newarray=0;
}
return $this->newarray;

} 





    //***** UPDATEDATABASE(); *****// 
function updatedatabase($fields,$data,$table,$where)
{
	 $this->dbconnectme();

$this->query="UPDATE ".$table." SET ";

$this->firststring=0;
$this->quantity = count($fields);
$this->quantity=$this->quantity-1;

for ($i=0;$i<=$this->quantity;$i++){
	if($this->firststring==0){
		$this->info=$fields[$i]."='".$data[$i]."'";
		$this->firststring=1;
	} else {
	$this->info=$this->info.", ".$fields[$i]."='".$data[$i]."'";
	}
}
$this->query=$this->query." ".$this->info." WHERE ".$where;



if(mysql_query($this->query)){
	$this->final=1;

} else {

	$this->final=0;
}
return $this->final;

} 




    //***** DELETEENTRY(); *****// 
function deleteentry($field,$data,$table)
{
	 $this->dbconnectme();

$this->query="DELETE FROM ".$table." WHERE ".$field."='".$data."'";;

if(mysql_query($this->query)){
	$this->final=1;

} else {
	$this->final=0;
}
return $this->final;
} 





    //***** USERCHECK(); *****// 
function usercheck($table,$user,$codex)
{

	 $this->dbconnectme();
	
	$this->query="SELECT * FROM ".$table." WHERE user='".$user."' LIMIT 1";
	
	$this->result=mysql_query($this->query);

while($this->row = mysql_fetch_array($this->result))
  {
	  $this->realcodex=$this->row['codex'];
	  $this->id=$this->row['id'];
  }
	
	$this->usercodexcheck=sha1($codex);
	
	$this->realcodexcheck=base64_decode($this->realcodex);
	$this->cryptie = new encryption_class;
	$this->key=$this->master;
	$this->checkthis=$this->cryptie->decrypt($this->key, $this->realcodexcheck);
	
	if($this->checkthis==$this->usercodexcheck){
		$this->access="6r4n73d";
	} else {
		$this->access="0";
	}
	
	return $this->access;
	
	
} 





    //***** ENCODEPASS(); *****// 
function encodepass($codex)
{
	$this->hiddencodex=sha1($codex);
	$this->crypt = new encryption_class;
	$this->key=$this->master;
	$this->almost_done = $this->crypt->encrypt($this->key, $this->hiddencodex);
	$this->ready1=base64_encode($this->almost_done);
		
	return $this->ready1;
} 




     //***** CHECKWHERE(); *****// MAGIC FUNCTION
function checkwhere($myquery,$table)
{
	 $this->dbconnectme();

	$this->query="SELECT * FROM ".$table." WHERE ".$myquery." LIMIT 1";
	
	$this->result = mysql_query($this->query);
$this->final=0;
while($this->row = mysql_fetch_array($this->result))
  {
	  $this->final=$this->final+1;
  }
  
return $this->final;

} 

} // End of class









class encryption_class {

    var $scramble1;     // 1st string of ASCII characters
    var $scramble2;     // 2nd string of ASCII characters

    var $errors;        // array of error messages
    var $adj;           // 1st adjustment value (optional)
    var $mod;           // 2nd adjustment value (optional)

    // ****************************************************************************
    // class constructor
    // ****************************************************************************
    function encryption_class ()
    {
        $this->errors = array();

        // Each of these two strings must contain the same characters, but in a different order.
        // Use only printable characters from the ASCII table.
        // Do not use single quote, double quote or backslash as these have special meanings in PHP.
        // Each character can only appear once in each string.
        $this->scramble1 = '! #$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~';
        $this->scramble2 = 'f^jAE]okIOzU[2&q1{3`h5w_794p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.Jx)HiQ!#$~(;Lt-R}Ma,NvW+Ynb*0X';

        if (strlen($this->scramble1) <> strlen($this->scramble2)) {
            trigger_error('** SCRAMBLE1 is not same length as SCRAMBLE2 **', E_USER_ERROR);
        } // if

        $this->adj = 1.75;  // this value is added to the rolling fudgefactors
        $this->mod = 3;     // if divisible by this the adjustment is made negative

    } // constructor

    // ****************************************************************************
    function decrypt ($key, $source)
    // decrypt string into its original form
    {
        $this->errors = array();

        // convert $key into a sequence of numbers
        $fudgefactor = $this->_convertKey($key);
        if ($this->errors) return;

        if (empty($source)) {
            $this->errors[] = 'No value has been supplied for decryption';
            return;
        } // if

        $target = null;
        $factor2 = 0;

        for ($i = 0; $i < strlen($source); $i++) {
            // extract a (multibyte) character from $source
            if (function_exists('mb_substr')) {
                $char2 = mb_substr($source, $i, 1);
            } else {
                $char2 = substr($source, $i, 1);
            } // if

            // identify its position in $scramble2
            $num2 = strpos($this->scramble2, $char2);
            if ($num2 === false) {
                $this->errors[] = "Source string contains an invalid character ($char2)";
                return;
            } // if

            // get an adjustment value using $fudgefactor
            $adj     = $this->_applyFudgeFactor($fudgefactor);

            $factor1 = $factor2 + $adj;                 // accumulate in $factor1
            $num1    = $num2 - round($factor1);         // generate offset for $scramble1
            $num1    = $this->_checkRange($num1);       // check range
            $factor2 = $factor1 + $num2;                // accumulate in $factor2

            // extract (multibyte) character from $scramble1
            if (function_exists('mb_substr')) {
                $char1 = mb_substr($this->scramble1, $num1, 1);
            } else {
                $char1 = substr($this->scramble1, $num1, 1);
            } // if

            // append to $target string
            $target .= $char1;

            //echo "char1=$char1, num1=$num1, adj= $adj, factor1= $factor1, num2=$num2, char2=$char2, factor2= $factor2<br />\n";

        } // for

        return rtrim($target);

    } // decrypt

    // ****************************************************************************
    function encrypt ($key, $source, $sourcelen = 0)
    // encrypt string into a garbled form
    {
        $this->errors = array();

        // convert $key into a sequence of numbers
        $fudgefactor = $this->_convertKey($key);
        if ($this->errors) return;

        if (empty($source)) {
            $this->errors[] = 'No value has been supplied for encryption';
            return;
        } // if

        // pad $source with spaces up to $sourcelen
        $source = str_pad($source, $sourcelen);

        $target = null;
        $factor2 = 0;

        for ($i = 0; $i < strlen($source); $i++) {
            // extract a (multibyte) character from $source
            if (function_exists('mb_substr')) {
                $char1 = mb_substr($source, $i, 1);
            } else {
                $char1 = substr($source, $i, 1);
            } // if

            // identify its position in $scramble1
            $num1 = strpos($this->scramble1, $char1);
            if ($num1 === false) {
                $this->errors[] = "Source string contains an invalid character ($char1)";
                return;
            } // if

            // get an adjustment value using $fudgefactor
            $adj     = $this->_applyFudgeFactor($fudgefactor);

            $factor1 = $factor2 + $adj;             // accumulate in $factor1
            $num2    = round($factor1) + $num1;     // generate offset for $scramble2
            $num2    = $this->_checkRange($num2);   // check range
            $factor2 = $factor1 + $num2;            // accumulate in $factor2

            // extract (multibyte) character from $scramble2
            if (function_exists('mb_substr')) {
                $char2 = mb_substr($this->scramble2, $num2, 1);
            } else {
                $char2 = substr($this->scramble2, $num2, 1);
            } // if

            // append to $target string
            $target .= $char2;

            //echo "char1=$char1, num1=$num1, adj= $adj, factor1= $factor1, num2=$num2, char2=$char2, factor2= $factor2<br />\n";

        } // for

        return $target;

    } // encrypt

    // ****************************************************************************
    function getAdjustment ()
    // return the adjustment value
    {
        return $this->adj;

    } // setAdjustment

    // ****************************************************************************
    function getModulus ()
    // return the modulus value
    {
        return $this->mod;

    } // setModulus

    // ****************************************************************************
    function setAdjustment ($adj)
    // set the adjustment value
    {
        $this->adj = (float)$adj;

    } // setAdjustment

    // ****************************************************************************
    function setModulus ($mod)
    // set the modulus value
    {
        $this->mod = (int)abs($mod);    // must be a positive whole number

    } // setModulus

    // ****************************************************************************
    // private methods
    // ****************************************************************************
    function _applyFudgeFactor (&$fudgefactor)
    // return an adjustment value  based on the contents of $fudgefactor
    // NOTE: $fudgefactor is passed by reference so that it can be modified
    {
        $fudge = array_shift($fudgefactor);     // extract 1st number from array
        $fudge = $fudge + $this->adj;           // add in adjustment value
        $fudgefactor[] = $fudge;                // put it back at end of array

        if (!empty($this->mod)) {               // if modifier has been supplied
            if ($fudge % $this->mod == 0) {     // if it is divisible by modifier
                $fudge = $fudge * -1;           // make it negative
            } // if
        } // if

        return $fudge;

    } // _applyFudgeFactor

    // ****************************************************************************
    function _checkRange ($num)
    // check that $num points to an entry in $this->scramble1
    {
        $num = round($num);         // round up to nearest whole number

        $limit = strlen($this->scramble1);

        while ($num >= $limit) {
            $num = $num - $limit;   // value too high, so reduce it
        } // while
        while ($num < 0) {
            $num = $num + $limit;   // value too low, so increase it
        } // while

        return $num;

    } // _checkRange

    // ****************************************************************************
    function _convertKey ($key)
    // convert $key into an array of numbers
    {
        if (empty($key)) {
            $this->errors[] = 'No value has been supplied for the encryption key';
            return;
        } // if

        $array[] = strlen($key);    // first entry in array is length of $key

        $tot = 0;
        for ($i = 0; $i < strlen($key); $i++) {
            // extract a (multibyte) character from $key
            if (function_exists('mb_substr')) {
                $char = mb_substr($key, $i, 1);
            } else {
                $char = substr($key, $i, 1);
            } // if

            // identify its position in $scramble1
            $num = strpos($this->scramble1, $char);
            if ($num === false) {
                $this->errors[] = "Key contains an invalid character ($char)";
                return;
            } // if

            $array[] = $num;        // store in output array
            $tot = $tot + $num;     // accumulate total for later
        } // for

        $array[] = $tot;            // insert total as last entry in array

        return $array;

    } // _convertKey

// ****************************************************************************
} // end encryption_class
// ****************************************************************************


