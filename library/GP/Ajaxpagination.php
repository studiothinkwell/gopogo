<?php
/**
 * Ajax pagination class
 * Gopogo : Pagination class for ajax content loadinig.
 *
 * <p></p>
 *
 * @category gopogo pagination module
 * @package Library
 * @author   Mujaffar <mujaffar@techdharma.com>
 * @version  1.0
 * @copyright Copyright (c) 2010 Gopogo.com. (http://www.gopogo.com)
 * @path /library/GP/
 */

class GP_Ajaxpagination
	{
		public $dbadapter;
		public $url;
		public $area;
		public $page;
                public $param;
                public $total;
                // Constructor to initialize values
		function __construct($db,$url,$area,$page,$pageCount) {
                    $this->url = trim($url);
                    $this->area = trim($area);
                    $this->page = $page;
                    //$this->dbadapter = Zend_Registry::get($db.'_dbAdapter');
                    $this->pageCount = $pageCount;
		}

                // Call pagination to generate pagination result
		function pagination($spName, $param, $size) {
                    // Code to check user is loged in or not
                    $session = GP_GPAuth::getSession();
                    $res = $this->callSP($spName, $param, $offset=0, 100000000);
		    $num = count($res);
                    $this->_total = $num;
		    $pageNum = '1';
                    $url = $this->url;
                    $areaId=$this->area;
                    if($this->page)
                        $pageNum = $this->page;
                    $result = $this->get_pagination($url,$areaId,$spName,$size,$num,$pageNum,$param);
                    return ($result);
		 }

		 function get_pagination($url,$areaId,$spName,$size,$num,$pageNum,$param) {
                     // create user model object
                     $user = new Application_Model_DbTable_User();
		 	//If there are some rows then start the pagination
			if ($num>0) {
				//Determine the maxpage and the offset for the query
				$maxPage = ceil($num/$size);

				$offset = ($pageNum - 1) * $size;

				//Initiate the navigation bar
				$nav  = '';

//					get low end
				$page = $pageNum-floor($this->pageCount/2);
//				get upperbound
				$upper =$pageNum+floor($this->pageCount/2);

				if ($page <=0)
				 {
					$page=1;
				 }

				if ($upper >$maxPage)
				{
					$upper =$maxPage;
				}

			//Make sure there are 5 numbers (2 before, 2 after and current
//					if ($upper-$page <4)
				if ($upper-$page < $this->pageCount-1)
					{
				        //We know that one of the page has maxed out
						//check which one it is
						//echo "$upper >=$maxPage<br>";
					if ($upper >=$maxPage)
						{
							//the upper end has maxed, put more
							//echo "to begining<br>";
							$dif =$maxPage-$page;
							//echo "$dif<br>";
								if ($dif==floor($this->pageCount/2)-1)
								{
									$page=$page-(floor($this->pageCount/2)-1);
								}
								elseif ($dif==(floor($this->pageCount/2)))
								{
									$page=$page-floor($this->pageCount/2);
								}
								elseif ($dif==(floor($this->pageCount/2)+1))
								{
									$page=$page-1;
								}
						}
						elseif ($page <=1)
						{
							//its the low end, add to upper end
							//echo "to upper<br>";
							$dif =$upper-1;
							if ($dif==(floor($this->pageCount/2))) {
								$upper=$upper+(floor($this->pageCount/2));
							}
							elseif ($dif==(floor($this->pageCount/2))+1) {
                                                            $upper=$upper+1;
							}
							elseif ($dif==(floor($this->pageCount/2))-1) {
							    $upper=$upper+(floor($this->pageCount/2))+1;
							}
						}
					}
					if ($page <=0) {
						$page=1;
					}

					if ($upper >$maxPage) {
						$upper =$maxPage;
					}
                                        
                                        $from = (($pageNum - 1) * $size)+1  ;
                                        $to = $pageNum * $size;
                                        if($this->_total < $to) {
                                            $to = $this->_total;
                                        }
                                        $nav.= "<span class='pgnactv'>";
                                        $nav.= $from." - ".$to." of ".$this->_total;
                                        $nav.= "</span>";
			//images for buttons//
			$path = '/tutorial1/public/';
			$PrevIc  = 'pgnactv';
			$FirstIc = 'pgnactv';
			$NextIc  = 'pgnactv';
			$LastIc  = 'pgnactv';

			$dPrevIc = 'pgninactv';
			$dFirstIc= 'pgninactv';
			$dNextIc = 'pgninactv';
			$dLastIc = 'pgninactv';


			//These are the button links for first/previous enabled/disabled
				if ($pageNum > 1) {
					$page  = $pageNum - 1;
					$prev  = "<a href='javascript:void(0)' onclick='javascript:showPage(\"".$url."\",\"".$areaId."\",\"".$page."\");return false;' class='".$PrevIc."' >Prev</a>";
					$first  = "<a href='javascript:void(0)' onclick='javascript:showPage(\"".$url."\",\"".$areaId."\",\"1\");' class='".$FirstIc."'>First</a>";
				}
				else {
					$prev  = "<span class='".$dPrevIc."' >Prev</a>";
					$first  = "<span class='".$dFirstIc."'>First</a>";
				}

				//These are the button links for next/last enabled/disabled
				if ($pageNum < $maxPage AND $upper <= $maxPage) {
                                    $page = $pageNum + 1;
                                    $next  = "<a href='javascript:void(0)' onclick='javascript:showPage(\"".$url."\",\"".$areaId."\",\"".$page."\");return false;' class='".$NextIc."'>Next</a>";
                                    $last = "<a href='javascript:void(0)' onclick='javascript:showPage(\"".$url."\",\"".$areaId."\",\"".$maxPage."\");return false;' class='".$LastIc."'>Last</a>";
				}
				 else {
                                    $next  = "<span class='".$dNextIc."' >Next</a>";
                                    $last  = "<span class='".$dLastIc."' >Last</a>";
				 }

			if ($maxPage>1) {
                            // print the navigation link
                            $pagination = $first . $prev . $nav . $next . $last;
			}
			if ($maxPage>1) {
                            $result = $this->callSP($spName, $param, $offset, $size);
			 }
			else {
                            $result = $this->callSP($spName, $param, $offset, $size);
			}

			if(sizeof($result)>0){
		  	    $record['list'] = $result;
                            $record['paging'] = $pagination;
			}
			else
			{
                            $pageNum=$pageNum-1;
                            return ($this->get_pagination($url,$areaId,$spName,$size,$num,$pageNum));
			}
		 return $record;
	}

    }

    // Function to call stored procedure and return result
    function callSP($spName, $param, $offset, $limit) {
        // create user model object
        $user = new Application_Model_DbTable_User();
        switch ($spName) {
            case 'getUserMessageList' : 
                return $user->getUserMessageList($param['userId'],$offset,$limit);
            break;

            case '' :
            return ;
            break;

            case '' :
            return ;
            break;
        }
    }
}

