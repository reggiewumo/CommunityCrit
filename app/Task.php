<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
	protected $fillable = [
		'name',
		'text',
		'parent_id',
	];

	/**
	 * Returns whether or not the Task has a parent
	 *
	 * @return bool
	 */
	public function hasParent()
	{
		return isset( $this->parent_id );
	}

	/**
	 * Returns whether or not the Task has children
	 *
	 * @return bool
	 */
	public function hasChildren()
	{
		return $this->children->isNotEmpty();
	}

	/**
	 * Get parent
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parent()
	{
		return $this->belongsTo( 'App\Task', 'parent_id' );
	}

	/**
	 * Get children tasks
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function children()
	{
		return $this->hasMany( 'App\Task', 'parent_id' );
	}

	/**
	 * Alias for children
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function subtasks()
	{
		return $this->children();
	}
}
