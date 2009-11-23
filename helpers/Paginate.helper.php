<?php
class Paginate
{
	public $perPage;
	public $items;
	public $sCurrentPage;
	public $page_link;
	private $pages;
	
	function paginate($perPage, $items, $sCurrentPage, $page_link = "")
	{
		$this->perPage = $perPage;
		$this->items = $items;
		$this->sCurrentPage = (int)$sCurrentPage;
		$this->page_link = $page_link;
		
		if(empty($this->sCurrentPage))
			$this->sCurrentPage = 1;
		
		if($this->items > 0)
			$this->pages = ceil($this->items / $this->perPage);
		else
			$this->pages = 0;
		
		if($this->sCurrentPage > $this->pages)
			$this->sCurrentPage = $this->pages;
	}
	
	## GET #########
	function get_start()
	{
		if($this->sCurrentPage > 1)
			$start = ($this->sCurrentPage * $this->perPage) - $this->perPage;
		else
			$start = 0;
		
		return $start;
	}
	function get_num_pages()
	{
		return $this->pages;
	}
	################
	
	## BUILD #######
	function build_array()
	{
		$aPaging = array(
			"back" => array(
				"page" => $this->sCurrentPage - 1,
				"use" => true
			),
			"next" => array(
				"page" => $this->sCurrentPage + 1,
				"use" => true
			)
		);
		
		if(($this->sCurrentPage - 1) < 1 || $this->sCurrentPage == 1)
			$aPaging["back"]["use"] = false;
		
		if($this->sCurrentPage == $this->pages)
			$aPaging["next"]["use"] = false;
		
		return $aPaging;
	}
	function build_array_arround($around = 0, $text_back = "Back", $text_next = "Next")
	{
		$aPaging = array();
		
		## BACK ##
		if(($this->sCurrentPage - $around) > 1)
			$aPaging[] = array(
				"page" => $this->sCurrentPage - 1
				,"text" => $text_back
				,"type" => "move"
			);
		
		## Around Left ##
		$around_tmp = $around;
		while($around_tmp > 0)
		{
			$page = $this->sCurrentPage - $around_tmp;
			if($page > 0)
				$aPaging[] = array(
					"page" => $page
					,"text" => $page
					,"type" => "around"
				);
			
			$around_tmp--;
		}
		
		## Current Page ##
		$aPaging[] = array(
			"page" => $this->sCurrentPage
			,"text" => $this->sCurrentPage
			,"type" => "cur"
		);
		
		$around_tmp = 1;
		while($around_tmp <= $around && ($this->sCurrentPage + $around_tmp) <= $this->pages)
		{
			$page = $this->sCurrentPage + $around_tmp;
			$aPaging[] = array(
				"page" => $page
				,"text" => $page
				,"type" => "around"
			);
			
			$around_tmp++;
		}
		
		if(($this->sCurrentPage + $around) < $this->pages)
			$aPaging[] = array(
				"page" => $this->sCurrentPage + 1
				,"text" => $text_next
				,"type" => "move"
			);
		
		return $aPaging;
	}
	function build_simple()
	{
		$html = "";
		
		if($this->pages > 1)
		{
			if($this->cpage > 1)
			{
				$back = $this->cpage - 1;
				$html .= "<a href=\"".$this->plink.$back."/\">&laquo;Back</a> | ";
			}
			else
				$html .= "&laquo;Back | ";
			
			for($i=1;$i<=$this->pages;$i++)
			{
				if($i == $this->cpage)
					$html .= $i;
				else
					$html .= "<a href=\"".$this->plink.$i."/\">".$i."</a>";
			
				if($i < $this->pages)
					$html .= "-";
			}
		
			if($this->cpage == $this->pages)
				$html .= " | Next&raquo;";
			else
			{
				$next = $this->cpage + 1;
				$html .= " | <a href=\"".$this->plink.$next."/\">Next&raquo;</a>";
			}
		}
			
		return $html;
	}
	################
}