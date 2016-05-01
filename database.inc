<?php
	class MyDB extends SQLite3
	{
		function __construct()
		{
			$this->open('Data.db3');
		}
	}

	function buildQuery($categoryCount)
	{
	    $whereClause = "WHERE Category in (";
		for ($i = 0; $i < $categoryCount; $i++)
		{
			$whereClause .= ":cat" . $i;
			if ($i + 1 < $categoryCount)
			{
				$whereClause .= ", ";
			}
		}
		$whereClause .= ") ";

		$whereClause .= "AND Type = :type ";

		return "SELECT DISTINCT Text FROM Term " .
				"INNER JOIN CategoryLink ON CategoryLink.TermId = Term.Id " .
				$whereClause .
				"ORDER BY RANDOM() LIMIT :limit";
	}
	
	function getTerms($type, $cats)
	{		
		$db = new MyDB();		
		$query = buildQuery(count($cats));
		
		$statement = $db->prepare($query);
		$statement->bindValue(":type", $type);
		$statement->bindValue(":limit", 10);
		
		for($i = 0; $i < count($cats); $i++)
		{
			$statement->bindValue(":cat" . $i, $cats[$i]);
		}
				
		$result = $statement->execute();		
		return getArrayResults($result);
	}
	
	function getCategories()
	{
		$db = new MyDB();
		$result = $db->query("SELECT DISTINCT Category from CategoryLink ORDER BY Category");
				
	    return getArrayResults($result);
	}
	
	function getArrayResults($result)
	{
		$output = array();
		while($row = $result->fetchArray()) {			
			array_push($output, $row[0]);
		}
		
		return $output;
	}
?>