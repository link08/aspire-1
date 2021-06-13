<?php
namespace Tests;

use App\Models\Loan;
use App\Models\EmiTransaction;

class ModelStructures
{
	/**
     * Get all attributes of a model by model name
     *
     * @param  string $modelName
     * @return array  $attributes
     */
	public static function getStructureFor($modelName)
	{
		switch($modelName) {
			case 'Loan':
				$model = new Loan;
				break;

			case 'EmiTransaction':
				$model = new EmiTransaction;
				break;

			default:
				return null;
		}

		return self::getAllAttributes($model);
	}

	/**
     * Get all attributes of a model
     *
     * @param  App\Model $model
     * @return array 	 $attributes
     */
	private static function getAllAttributes($model)
	{
		$attributes = array_merge($model->getFillable(), array_keys($model->getcasts()));
		return array_unique($attributes);
	}
}