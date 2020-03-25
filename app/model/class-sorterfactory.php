<?php

namespace IFM;

class Model_SorterFactory
{

	public function get_sorter($type)
	{

		switch (strtolower($type)) {

			case 'news-aggregator':
				$sorter = new Model_Aggregator();
				break;
		}

		return $sorter;
	}
}
