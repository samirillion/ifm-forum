<?php
namespace IFM;

class sorterFactory
{

	public function get_sorter($type)
	{

		switch (strtolower($type)) {

			case 'news-aggregator':
				$sorter = new newsAggregator();
				break;
		}

		return $sorter;
	}
}
