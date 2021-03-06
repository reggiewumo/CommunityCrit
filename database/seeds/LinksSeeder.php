<?php

use App\Link;
use Illuminate\Database\Seeder;

class LinksSeeder extends Seeder
{
	public $data = [
        // link types: 1. EVSO; 2. 
        [
            'type' => '1',
            'image' => '0',
            'link_type' => '1',
            'text' => '<strong>14th Street Promenade Workshop</strong><br>
                        The height limits of East Village/downtown (440 feet) and Barrio Logan (40 feet) converge here. Height should be treated in a sensitive manner. It is a great opportunity for pedestrians to pause and do a 360 of what surrounds them.',
            'idea_id' => '1',
            'user_id' => '3',
            'hidden' => '0',
            'status' => 1,
        ],
        [
            'type' => '1',
            'image' => '0',
            'link_type' => '1',
            'text' => '<strong>East Village South Draft Focus Plan</strong><br>
                        <b>Lighting:</b><br>
                        <ul>
                            <li>Use lighting as a way to further distinguish and differentiate East Village South from other downtown neighborhoods. A professional lighting designer should be retained to create a plan to make East Village South subtly unique at night.</li>
                            <li>No neon stripes permitted.</li>
                            <li>No digital billboards or moving images permitted except behind first story storefront glass.</li>
                        </ul>',
            'idea_id' => '1',
            'user_id' => '3',
            'hidden' => '0',
            'status' => 1,
        ],
        [
            'type' => '1',
            'image' => '0',
            'link_type' => '2',
            'text' => '<strong>East Village South Draft Focus Plan</strong><br>
                    As 14th Street turns to the south at Logan Avenue, there is a perfect opportunity to create a major connection point between Barrio Logan and the East Village neighborhood. For purposes of this Focus Plan it has been named this the Nudillo (Spanish for knuckle) to represent <b>the gateway between the two communities</b>...[it] can be enhanced to promote gathering and social interaction. It will become an important public space and celebrate camaraderie between the two communities.',
            'idea_id' => '3',
            'user_id' => '3',
            'hidden' => '0',
            'status' => 1,
        ],
        [
            'type' => '1',
            'image' => '0',
            'link_type' => '4',
            'text' => '[D]owntown residents in attendance…dislike walking in the city. Reasons include upkept sidewalks and streets, the growing homeless population, lack of retail, cultural institutions, parks, and public space.',
            'idea_id' => '2',
            'user_id' => '3',
            'hidden' => '0',
            'status' => 1,
        ],
        [
            'type' => '1',
            'image' => '0',
            'link_type' => '5',
            'text' => 'Columbus Circle in New York City was voted the best roundabout in the entire world! It has a tower structure in the middle, a plaza around that for people to walk, green spaces separating the plaza from traffic, and three pedestrian walkways to help people enter and exit the center of the roundabout.',
            'idea_id' => '3',
            'user_id' => '3',
            'hidden' => '0',
            'status' => 1,
        ],
        [
            'type' => '1',
            'image' => '0',
            'link_type' => '5',
            'text' => 'There are so many beautiful murals at Chicano Park that celebrate the heritage of the surrounding community. I wonder if local artists might be willing to collaboratively decorate whatever ends up being built at El Nudillo in a similar way?',
            'idea_id' => '2',
            'user_id' => '3',
            'hidden' => '0',
            'status' => 1,
        ],
        [
            'type' => '1',
            'image' => '0',
            'link_type' => '1',
            'text' => '<strong>San Diego General Plan</strong></br>San Diego has a semi-arid coastal climate with coastal areas receiving an average of ten inches of rain annually. The historically reliable water supply is credited to its ability to import and store water supplies from the Colorado River and Northern California. However, these imported water supplies are limited, and to meet the needs of population increases, economic growth, and competing regional demands, San Diego must develop additional water resources to ensure an adequate supply for present and future generations.',
            'idea_id' => '4',
            'user_id' => '3',
            'hidden' => '0',
            'status' => 1,
        ],
        [
            'type' => '1',
            'image' => '0',
            'link_type' => '6',
            'text' => 'I grew up in San Diego, and I remember when the permanent water use restrictions went into effect. If restaurants are not allowed to serve drinking water without request, and if we cannot water our lawns after 10am and before 6pm, I think it is really irresponsible to think about using water in a fountain.',
            'idea_id' => '4',
            'user_id' => '3',
            'hidden' => '0',
            'status' => 1,
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ( $this->data as $link ) {
            Link::create($link);
        }
    }
}
