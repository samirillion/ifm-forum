<?php

namespace IFM;

class Model_sorterFactory
{

	public function get_sorter($type)
	{

		switch (strtolower($type)) {

			case 'news-aggregator':
				$sorter = new Model_newsAggregator();
				break;
		}

		return $sorter;
	}
}
