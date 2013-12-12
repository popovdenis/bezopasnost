<?php
    class Test extends Controller
{

    function __construct()
    {
        parent::Controller();
    }

    /**
     */
    function index()
    {
        $this->load->model( 'items_mdl', 'items' );

        $items = $this->items->get_item();
        if( !empty( $items ) )
        {
            foreach( $items as $item )
            {
                $categories = $this->items->get_item_category( $item->item_id );
                $categoriesIds = array();
                if( !empty( $categories ) )
                {
                    foreach( $categories as $category )
                    {
                        $categoriesIds[] = $category->category_id;
                    }
                }
                if( !empty( $categoriesIds ) )
                {
                    $this->seo->setSeoData( $item, $categoriesIds );
                }
            }
        }
    }

    public function testParser()
    {
        require_once( APPPATH.'/libraries/PHPExcel/PHPExcel.php');
        require_once( APPPATH.'/libraries/CSV.php');

        $full_path = 'Price_SPV_2010_part1.xls';

        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized;
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load( $full_path );
        $activeSheet = $this->parseFile( $objPHPExcel );
    }

    private function parseFile(PHPExcel $objPHPExcel) {
        $sheet_str = '';
        $row_start = empty($row_start) ? 1 : ++$row_start;
        $issetCoordinates = false;
        $coordinateRow = 0;

        $customerReportSheets = array(
            'Сервіс'    =>  array(
                'title'     =>  array( 'name' => 'Назва', 'coordinate' => null ),
                'desc'      =>  array( 'name' => 'Опис', 'coordinate' => null ),
                'cost'      =>  array( 'name' => 'Ціна грн.з ПДВ', 'coordinate' => null )
            )
        );
        $reportSheetsData = array();

        $sheetNames = $objPHPExcel->getSheetNames();

        foreach($sheetNames as $index => $name)
        {
            if( array_key_exists( $name, $customerReportSheets ) )
            {
//            if( $index < 2 ) continue;
                $objWorksheet = $objPHPExcel->getSheetByName($name);
                $highestRow = $objWorksheet->getHighestRow();
                $highestColumn = $objWorksheet->getHighestColumn();

                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn)+1;

                for ($row = $row_start; $row <= $highestRow; ++$row) {
                    if( !empty( $customerReportSheets[$name]['title']['coordinate'] )
                    && !empty( $customerReportSheets[$name]['desc']['coordinate'] )
                    && !empty( $customerReportSheets[$name]['cost']['coordinate'] ) )
                    {
                        $row_start = ( $row );
                        break;
                    }
                    else
                    {
                        for ($col = 0; $col < $highestColumnIndex; ++$col)
                        {
                            $_value = trim( $objWorksheet->getCellByColumnAndRow($col, $row)->getValue() );
                            if( $_value == $customerReportSheets[$name]['title']['name'] )
                            {
                                $customerReportSheets[$name]['title']['coordinate'] = array( 'col' => $col, 'row' => $row );
                            }
                            elseif( $_value == $customerReportSheets[$name]['desc']['name'] )
                            {
                                $customerReportSheets[$name]['desc']['coordinate'] = array( 'col' => $col, 'row' => $row );
                            }
                            elseif( $_value == $customerReportSheets[$name]['cost']['name'] )
                            {
                                $customerReportSheets[$name]['cost']['coordinate'] = array( 'col' => $col, 'row' => $row );
                            }
                        }
                    }
                }

                $customerReportSheets[$name]['image']['col'] = array(1);
                $customerReportSheets[$name]['title']['col'] = array(1);
                $customerReportSheets[$name]['desc']['col'] = array(2, 3);
                $customerReportSheets[$name]['cost']['col'] = array(4);

                if( !empty( $customerReportSheets[$name]['title']['col'] )
                && !empty( $customerReportSheets[$name]['desc']['col'] )
                && !empty( $customerReportSheets[$name]['cost']['col'] ) )
                {
                    $columnTitle = $customerReportSheets[$name]['title']['col'];
                    $columnDesc = $customerReportSheets[$name]['desc']['col'];
                    $columnCost = $customerReportSheets[$name]['cost']['col'];

                    for ($row = $row_start; $row <= $highestRow; ++$row)
                    {
                        $value = "";
                        /*
                        foreach( $columnTitle as $col )
                        {*/
//                        echo "<pre>";
//                            var_dump(get_class_methods($objWorksheet->setCellValueExplicitByColumnAndRow(0, $row)));
//                        echo "</pre>";exit;  getHyperlink getValueBinder

                            $value .= trim( $objWorksheet->getCellByColumnAndRow( 1, $row )->getValue() );
//                        }
                        $reportSheetsData[$name][$row]['title'] = $value;

                        $value = "";
//                        foreach( $columnDesc as $col )
//                        {
                            $value = trim( $objWorksheet->getCellByColumnAndRow( 2, $row )->getValue() );
                            $value .= ". " . trim( $objWorksheet->getCellByColumnAndRow( 3, $row )->getValue() );
//                        }
                        $reportSheetsData[$name][$row]['desc'] = $value;

                        $value = "";
                        /*foreach( $columnCost as $col )
                        {*/
                            $value .= floatval( trim( $objWorksheet->getCellByColumnAndRow( 4, $row )->getValue() ) );
//                        }
                        $reportSheetsData[$name][$row]['cost'] = $value;

                        if( empty( $reportSheetsData[$name][$row]['cost'] ) )
                        {
                            unset( $reportSheetsData[$name][$row] );
                        }
                        elseif( !empty( $reportSheetsData[$name][$row]['cost'] )
                                && empty( $reportSheetsData[$name][$row]['title'] ) )
                        {
                            $reportSheetsData[$name][$row]['title'] = $reportSheetsData[$name][$row - 1]['title'];

                            $value = trim( $objWorksheet->getCellByColumnAndRow( 2, ( $row - 1 ) )->getValue() );
                            $value .= ". " . trim( $objWorksheet->getCellByColumnAndRow( 3, $row )->getValue() );

                            $reportSheetsData[$name][$row]['desc'] = $value;

                            /*if( empty( $reportSheetsData[$name][$row]['desc'] ) )
                            {
                                $reportSheetsData[$name][$row]['desc'] = $reportSheetsData[$name][$row-1]['desc'] . $reportSheetsData[$name][$row]['desc'];
                            }*/
                        }
                    }
                }
                echo "<pre>";
                    var_dump($reportSheetsData);
                echo "</pre>";exit;
            }
        }
		/*$sheet_str = '';
		$row_start = empty($row_start) ? 1 : ++$row_start;
		$empty_colls = 0;
		$empty_rows = 0;

		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$this->general_columns = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn)+1;

		for ($row = $row_start; $row <= $highestRow; ++$row) {
			$row_values_str = "";
			for ($col = 0; $col < $highestColumnIndex; ++$col) {
				$value = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
				if(empty($value)) {
					$empty_colls++;
					$value = $value = str_replace(" ", "", $value);
				} else {
					$value = mb_convert_encoding($value,"UTF-8", mb_detect_encoding($value));
					$symbols = array('`','~','!','@','#','$','%','^','&','*','(', ')','-','=',';',':','"',"'",'<','>','?');
					$value = str_replace($symbols, '', $value);
				}
                $value = mb_convert_encoding($value,"UTF-8", mb_detect_encoding($value));
				$row_values_str .= '"'.$value.'";';
			}
			if($empty_colls != $highestColumnIndex) $sheet_str .= $row_values_str."\r\n";
			$empty_colls = 0;

			if($empty_rows == 5) break;
		}
		return $sheet_str;*/
	}
}

?>