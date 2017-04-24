<?php
// $comment_object = array(
// 	0 => array('comment_ID' => 1, 'comment_content' => 'Comment1', 'comment_parent' => 0),
// 	1 => array('comment_ID' => 2, 'comment_content' => 'Comment2', 'comment_parent' => 1),
// 	2 => array('comment_ID' => 3, 'comment_content' => 'Comment3', 'comment_parent' => 2),
// 	3 => array('comment_ID' => 4, 'comment_content' => 'Comment4', 'comment_parent' => 0),
// 	4 => array('comment_ID' => 5, 'comment_content' => 'Comment5', 'comment_parent' => 3),
// 	5 => array('comment_ID' => 6, 'comment_content' => 'Comment6', 'comment_parent' => 0),
// 	6 => array('comment_ID' => 7, 'comment_content' => 'Comment7', 'comment_parent' => 4),
// 	7 => array('comment_ID' => 8, 'comment_content' => 'Comment8', 'comment_parent' => 0)
// );

//function for generating nested comments
// O(N^2) - Might need to rerun through the array again for every element in the array
function render($commentQuery, $parentId = 0, $depth = 0)
{
	echo str_repeat('  ', $depth);
	echo "<ul>\n";
	foreach ($commentQuery as $key => $comment) {
		if ($comment['comment_parent'] == $parentId) {
			echo str_repeat('  ', $depth+1);
			echo "<li>\n";
			echo str_repeat('  ', $depth+2);
			echo $comment['comment_content'];
			echo "\n";
			echo str_repeat('  ', $depth+1);
			echo "</li>\n";

			// Check through whole structure again. If we find our chidlren, print them
			$children = render($commentQuery, $comment['comment_ID'], $depth + 1);
		}
	}
	echo str_repeat('  ', $depth);
	echo "</ul>\n";
}

// O(N) - Will visit every node exactly once
function sort_by_parent($comment_object)
{
	$comments_by_parent = array();

	// Makes an array with (comment_parent => comment)
	foreach ($comment_object as $key => $comment)
	{
		$comment_parent = $comment['comment_parent'];

		// Add a default array to store children comments in
		if (!array_key_exists($comment_parent, $comments_by_parent))
		{
			$comments_by_parent[$comment_parent] = array();
		}

		// Append our comment
		$comments_by_parent[$comment_parent][] = $comment;
	}

	return $comments_by_parent;
}

// O(N) - Will visit each node exactly once (assuming no loops)
function build_comment_structure($obj, $currentID = 0, $depth = 0)
{
	// Quit out if we don't have any children
	if (!array_key_exists($currentID, $obj))
	{
		return;
	}
	$children = $obj[$currentID];

	// Each node prints its own contents, then prints the contents of its children
	echo str_repeat('  ', $depth)."<ul>\n";
	foreach ($children as $key => $comment)
	{
		echo str_repeat('  ', $depth+1)."<li>\n";
		echo str_repeat('  ', $depth+2).$comment['comment_content']."\n";
		echo str_repeat('  ', $depth+1)."</li>\n";

		// Print all our children
		build_comment_structure($obj, $comment['comment_ID'], $depth + 1);
	}
	echo str_repeat('  ', $depth)."</ul>\n";
}

//render($comment_object);

$sorted_comment_object = sort_by_parent($comment_object);
build_comment_structure($sorted_comment_object);

echo "Fin";
