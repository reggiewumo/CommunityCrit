<?php

use App\Idea;
use Illuminate\Database\Seeder;

class IdeasSeeder extends Seeder
{
	public $data = [
        [
            'name' => 'Build a tower',
            'text' => 'Build a tower that jumps along the way and is in the center of the intersection.',
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ( $this->data as $idea ) {
            Idea::create($idea);
        }
    }
}
