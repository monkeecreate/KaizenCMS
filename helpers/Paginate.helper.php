<?php
class Paginate
{
	public $per_page;
	public $items;
	public $cur_page;
	public $page_link;
	private $pages;
	
	function paginate($per_page, $items, $cur_page, $page_link = "")
	{
		$this->per_page = $per_page;
		$this->items = $items;
		$this->cur_page = (int)$cur_page;
		$this->page_link = $page_link;
		
		if(empty($this->cur_page))
			$this->cur_page = 1;
		
		$this->pages = ceil($this->items / $this->per_page);
		
		if($this->cur_page > $this->pages)
			$this->cur_page = $this->pages;
	}
	
	## GET #########
	function get_start()
	{
		if($this->cpage > 1)
			$start = ($this->cpage * $this->ppage) - $this->ppage;
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
	function build_array($around = 0, $text_back = "Back", $text_next = "Next")
	{
		$aPaging = array();
		
		## BACK ##
		if(($this->cur_page - $around) > 1)
			$aPaging[] = array(
				"page" => $this->cur_page - 1
				,"text" => $text_back
				,"type" => "move"
			);
		
		## Around Left ##
		$around_tmp = $around;
		while($around_tmp > 0)
		{
			$page = $this->cur_page - $around_tmp;
			$aPaging[] = array(
				"page" => $page
				,"text" => $page
				,"type" => "around"
			);
			
			$around_tmp--;
		}
		
		## Current Page ##
		$aPaging[] = array(
			"page" => $this->cur_page
			,"text" => $this->cur_page
			,"type" => "cur"
		);
		
		$around_tmp = 1;
		while($around_tmp <= $around && ($this->cur_page + $around_tmp) <= $this->pages)
		{
			$page = $this->cur_page + $around_tmp;
			$aPaging[] = array(
				"page" => $page
				,"text" => $page
				,"type" => "around"
			);
			
			$around_tmp++;
		}
		
		if(($this->cur_page + $around) < $this->pages)
			$aPaging[] = array(
				"page" => $this->cur_page + 1
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