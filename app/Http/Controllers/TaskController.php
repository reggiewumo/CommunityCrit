<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Http\Requests\FeedbackRequest;
use App\Task;
use App\User;
use App\Source;
use App\Response;
use App\Topic;
use App\Design_Idea;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class TaskController extends Controller
{
	/**
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	 * @throws AuthorizationException
	 */
	public function index()
	{
		$condition = \Auth::user()->condition;

		// Don't allow user through if they don't have a condition (means
		if ($condition === null) {
			throw new AuthorizationException();
		}

		// Mapping of conditions to template names;
		$views = [
			User::CONDITION_GENERIC_HOLISTIC => 'tasks.generic.holistic',
			User::CONDITION_GENERIC_MICROTASK_OPEN => 'tasks.generic.microtaskOpen',
			User::CONDITION_GENERIC_MICROTASK_CLOSED => 'tasks.generic.microtaskClosed',
			User::CONDITION_PERSONAL_HOLISTIC => 'tasks.personal.holistic',
			User::CONDITION_PERSONAL_MICROTASK_OPEN => 'tasks.personal.microtaskOpen',
			User::CONDITION_PERSONAL_MICROTASK_CLOSED => 'tasks.personal.microtaskClosed',
		];

		// Set template name based on condition
		$view = $views[$condition];

		// Decide which data to fetch
		switch($condition) {
			case User::CONDITION_PERSONAL_MICROTASK_CLOSED:
				$data = ['task' => \Auth::user()->recommendedTasks->first()];
				break;
			case User::CONDITION_GENERIC_MICROTASK_CLOSED:
				$data = ['task' => Task::find(1)]; // TODO: Change to assigned task later
				break;
			case User::CONDITION_PERSONAL_MICROTASK_OPEN:
				$tasks = Task::allLeaves()->get();
				$data = ['tasks' => $tasks];
				break;
			default:
				// Get first root task
				$rootTask = Task::root();

				// Return tasks and root task
				$data = ['tasks' => $rootTask->getDescendants(), 'rootTask' => $rootTask];
		}

		// Embed recommendations if needed
		if ($condition === User::CONDITION_PERSONAL_MICROTASK_OPEN ||
		    $condition === User::CONDITION_PERSONAL_HOLISTIC) {
			$data['recommendations'] = \Auth::user()->recommendedTasks->pluck('id');
		}

		return view($view, $data);
	}

    public function newTask( Request $request )
    {
        $task = new Task;
        $task->name = $request->get('name');
        $task->text = $request->get('text');
        if ( $task->save() ) {
            flash("Question submitted!")->success();
        } else {
            flash('Unable to save your question. Please contact us.')->error();
        }

        return redirect()->back();
    }

	/**
	 * Save feedback item for task
	 *
	 * @param FeedbackRequest $request
	 * @param Task $task
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function storeFeedback( FeedbackRequest $request, Task $task )
	{
//        $type = $request->get( 'type' );
        $input1 = $request->get( 'input1' );
        $input2 = $request->get( 'input2' );
        $input3 = $request->get( 'input3' );

		$feedback          = new Feedback;
//		$feedback->comment = $request->get( 'comment' );
		$feedback->user_id = \Auth::id();
		$feedback->task_id = $task->id;
        $feedback->type = $request->get( 'type' );
        $feedback->comment = $feedback->constructComment($feedback->type, $input1, $input2, $input3);

		$taskName = $task->name;

		if ( $feedback->save() ) {
			flash("Feedback submitted for ${taskName}!")->success();
		} else {
			flash('Unable to save your feedback. Please contact us.')->error();
		}

		return redirect()->back();
	}

    private function nextTask( $id )
    {
        //TODO tree traversal here --------------------
//        $task = Task::where('id',$id)->first();
//
//        //get first child
//        $next = $task->immediateDescendants()->first();
//
//        //tree traversal
//        if ($next == null) {
//            $next = $task;
//            while ($next->parent_id != null) {
//                //if has siblings, and parent is not branch question, go to sibling that isnt visited
//                $siblings = $next->getSiblings();
//                if (!$siblings->isEmpty() && $next->parent()->first()->type != Task::TYPE_BRANCH_QUESTION){
//                    $available = collect($siblings->pluck('id')->all())->diff(\Session::get('visited'));
//                    if (!$available->isEmpty()) {
//                        $next = Task::findMany($available)->first();
//                        break;
//                    }
//                }
//                $next = Task::find($next->parent_id);
//            }
//        }
//
//        if ($next->parent_id == null) {
//            return null;
//        }
//        else {
//            return $next->id;
//        }
        //--------------------------------------------

        //randomized tasks from all
        //--------------------------------------------
        $next_ids = collect(Task::all()->pluck('id')->all())->diff(\Session::get('visited'));
        if (!$next_ids->isEmpty())
            $next_id = $next_ids->random();
        else $next_id = null;
        return $next_id;
    }

    public function storeResponse( FeedbackRequest $request, Task $task )
    {
//        $type = $request->get( 'type' );
        $input1 = $request->get( 'input1' );
        $input2 = $request->get( 'input2' );
        $input3 = $request->get( 'input3' );

        $feedback          = new Feedback;
//		$feedback->comment = $request->get( 'comment' );
        $feedback->user_id = \Auth::id();
        $feedback->task_id = $task->id;
        $feedback->type = $request->get( 'type' );
        $feedback->comment = $feedback->constructComment($feedback->type, $input1, $input2, $input3);

        $taskName = $task->name;

        if ( $feedback->save() ) {
            flash("Feedback submitted for ${taskName}!")->success();
        } else {
            flash('Unable to save your feedback. Please contact us.')->error();
        }

        \Session::push('visited', $task->id);

        $next = $this->nextTask($task->id);

        if ($next != null) {
            return redirect()->action(
                'TaskController@show', ['id' => $next]
            );
        }
        else {
            return redirect()->action(
                'TaskController@allProjects'
            );
        }
    }

    public function skipQuestion(/**Request $request, **/Task $task)
    {
        \Session::push('visited', $task->id);

        $next = $this->nextTask($task->id);

        if ($next != null) {
            return redirect()->action(
                'TaskController@testShow', ['id' => $next]
            );
        }
        else {
            return redirect()->action(
//                'TaskController@allProjects'
                'TaskController@overview'
            );
        }
    }

    public function overview()
    {
        //refresh visited questions when seeing all projects
        \Session::forget('visited');
        \Session::put('visited', []);
//        $data = [];
        return view('overview');
    }

    /**
     * Display single task view
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function testShow( $id )
    {
        if ($id == 0) {
            $task = Task::all()->random(1)->first();
        }
        else {
            $task = Task::where('id', $id)->first();
        }

        if ($task == null) {
            abort(404);
        }

        $view = 'proto.test';

        $test = \Session::get('test');

		$title = $task->name;
        $options = $task->options;
        $data = ['task' => $task, 'title' => $title, 'options' => $options, 'test' => $test];
        if ($id == 0)
            return redirect()->action( 'TaskController@testShow', ['id' => $task->id]);
        return view($view, $data);
    }

    /**
     * show question
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show( $id )
    {
        $task = Task::where('id',$id)->first();

        if ($task == null) {
            abort(404);
        }

        if ($task->type == Task::TYPE_PROJECT) {
            $task = $task->immediateDescendants()->first();
        }

        $view = 'tasks.questions.question';

        $title = $task->name;
        $options = $task->options;
        $data = ['task' => $task, 'title' => $title, 'project' => $task->getRoot(), 'test' => implode(" | ",\Session::get('visited')), 'options' => $options];
        return view($view, $data);
    }

    /**
     * Display all facets
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allFacets()
    {
        $view = 'tasks.facets.listAll';
        $data['title'] = 'Facets';
		$data['facets'] = Task::getFacets();
        return view($view, $data);
    }

    public function singleFacet($slug)
    {
        $facet = Task::where('slug',$slug)->first();

        if ($facet == null) {
            abort(404);
        }

        $view = 'tasks.facets.singleFacet';

        $data['title'] = $facet->name;
        $data['tasks'] = $facet->quotes;
        $data['facet'] = $facet;
        return view($view, $data);
    }

    public function allSources()
    {
        $view = 'tasks.sources.listAll';
        $data['title'] = 'Sources';
        $data['sources'] = Task::getSources();
        return view($view, $data);
    }

    public function singleSource($slug)
    {
        $source = Task::where('slug',$slug)->first();

        if ($source == null) {
            abort(404);
        }

        $view = 'tasks.sources.singleSource';

        $data['title'] = $source->name;
        $data['source'] = $source;
        $data['quotes'] = $source->sourceHasQuotes;
//        $data['quotes'] = Task::get()->where('source_id',25);
        return view($view, $data);
    }

    public function allProjects()
    {
        $view = 'tasks.questions.projects';
        $data['title'] = 'Projects';
        $data['projects'] = Task::getProjects();

        //refresh visited questions when seeing all projects
        \Session::forget('visited');
        \Session::put('visited', []);

        return view($view, $data);
    }

    public function quote($id)
    {
        $quote = Task::where('id',$id)->first();

        if ($quote == null) {
            abort(404);
        }

        $view = 'tasks.quote';

        $data['title'] = $quote->name;
        $data['quote'] = $quote;
        return view($view, $data);
    }

    public function testStoreResponse( Request $request, Task $task )
    {
        $feedback          = new Feedback;
//		$feedback->comment = $request->get( 'comment' );
        $feedback->user_id = \Auth::id();
        $feedback->task_id = $task->id;
        $feedback->type = 'custom';

        $feedback->comment = $request->get( 'option' );

        $taskName = $task->name;

        if ( $feedback->save() ) {
            flash("Feedback submitted for ${taskName}!")->success();
        } else {
            flash('Unable to save your feedback. Please contact us.')->error();
        }

        return redirect()->back();
    }

    public function testDashboard()
    {
        $view = 'proto.testtable';
        $data['cols'] = Topic::all();
        $data['rows'] = Design_Idea::all();

        return view($view, $data);
    }
}
