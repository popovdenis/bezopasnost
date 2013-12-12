<?php
  class Pd_CSV
  {
    protected $_name      = null;
    protected $_fil       = null;
    protected $_delimiter = ',';
    protected $_titles    = null;

    /**
    * @desc
    * @var $filename - name of file
    * @var $mode - mode of open file
    * 'r' Open for reading only; place the file pointer at the beginning of the file.
    * 'r+' Open for reading and writing; place the file pointer at the beginning of the file.
    * 'w' Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
    * 'w+' Open for reading and writing; place the file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
    * 'a' Open for writing only; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
    * 'a+' Open for reading and writing; place the file pointer at the end of the file. If the file does not exist, attempt to create it.
    * 'x' Create and open for writing only; place the file pointer at the beginning of the file. If the file already exists, the fopen() call will fail by returning FALSE and generating an error of level E_WARNING. If the file does not exist, attempt to create it. This is equivalent to specifying O_EXCL|O_CREAT flags for the underlying open(2) system call. This option is supported in PHP 4.3.2 and later, and only works for local files.
    * 'x+' Create and open for reading and writing; place the file pointer at the beginning of the file. If the file already exists, the fopen() call will fail by returning FALSE and generating an error of level E_WARNING. If the file does not exist, attempt to create it. This is equivalent to specifying O_EXCL|O_CREAT flags for the underlying open(2) system call. This option is supported in PHP 4.3.2 and later, and only works for local files.
    */
    public function __construct( $fileName, $mode, $delimiter = ',' )
    {
        $this->_fil       = fopen( $fileName, $mode );
        $this->_delimiter = $delimiter;
    }

    public function __destruct()
    {
        fclose( $this->_fil );
    }

    public function getLine()
    {
      if ( !empty( $this->_fil ) )
      {
          return fgetcsv($this->_fil, null, $this->_delimiter);
      }
      else
      {
          return null;
      }
    }

    public function getArray( $skipFirstLine = null, $useFirstLineAsKey = null )
    {
      $result  = null;
      $isFirst = true;
      if ( !empty( $this->_fil ) )
      {
        while ( $row = fgetcsv($this->_fil, 0, $this->_delimiter ) )
        {
          if ( !empty( $skipFirstLine ) && $isFirst )
          {
            $this->_titles = $row;
            $isFirst = false;
          }
          else
          {
            if ( $useFirstLineAsKey !== null && $skipFirstLine )
            {
              $tmpRow = array();
              foreach( $row as $key => $item )
              {
                $tmpRow[ $this->_titles[$key] ] = $item;
              }
              $result[] = $tmpRow;
              unset($tmpRow);
            }
            else
            {
              $result[] = $row;
            }
          }
        }
      }

      return $result;
    }

    public function getLines( $from, $count, $skipFirstLine = null )
    {
      $arrayCSV = $this->getArray();
    }

    public function putLine( $fields )
    {
        return fputcsv( $this->_fil, $fields, $this->_delimiter );
    }

    public function putArray( $data )
    {
        foreach ( $data as $fields )
        {
            $this->putLine( $fields );
        }
    }

  }
?>
