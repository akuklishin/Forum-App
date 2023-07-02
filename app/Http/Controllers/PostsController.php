<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Subforum;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $posts = DB::table('posts')
            ->select('posts.id', 'userId', 'subforumId', 'name  as author', 'subforumName', 'title', 'content', 'creationTime', 'imagePath', 'posts.scoreCount', 'users.rating', DB::raw('DATEDIFF(CURRENT_DATE(), creationTime) as createdDaysAgo'))
            ->join('users', 'posts.userId', '=', 'users.id')
            ->join('subforums', 'posts.subforumId', '=', 'subforums.id')
            ->having('createdDaysAgo', "<", 7)
            ->orderBy('scoreCount', 'desc')
            ->orderBy('creationTime', 'desc')
            ->paginate(10);

        return view('index', [
            'posts' => $posts
        ]);
    }

    //search functionality
    public function search(Request $request)
    {

        //form validation
        $request->validate([
            'search' => 'required|min:3'
        ]);

        $get = strip_tags($request->search);
        $subforums = Subforum::where('subforumName', 'LIKE', '%' . $get . '%')->get();
        $postsTitles = Post::where('title', 'LIKE', '%' . $get . '%')->get();
        return view('search', compact('subforums', 'postsTitles', 'get'));
    }
    //sort index by top
    public function top()
    {

        $posts = DB::table('posts')
            ->select('posts.id', 'userId', 'subforumId', 'name  as author', 'subforumName', 'title', 'content', 'creationTime', 'imagePath', 'posts.scoreCount', 'users.rating')
            ->join('users', 'posts.userId', '=', 'users.id')
            ->join('subforums', 'posts.subforumId', '=', 'subforums.id')
            ->orderBy('scoreCount', 'desc')
            ->paginate(10);

        return view('index', [
            'posts' => $posts
        ]);
    }

    //sort index by new
    public function new()
    {
        $posts = DB::table('posts')
            ->select('posts.id', 'userId', 'subforumId', 'name  as author', 'subforumName', 'title', 'content', 'creationTime', 'imagePath', 'posts.scoreCount', 'users.rating')
            ->join('users', 'posts.userId', '=', 'users.id')
            ->join('subforums', 'posts.subforumId', '=', 'subforums.id')
            ->orderBy('creationTime', 'desc')
            ->paginate(10);

        return view('index', [
            'posts' => $posts
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()) {
            return redirect('/login');
        } else {
            $loggedUser = Auth::user()->name;

            return view('create', [
                'loggedUser' => $loggedUser
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        //form validation
        $request->validate([
            'subforum' => 'required',
            'title' => 'required|min:2|max:255',
            'content' => 'required|min:2|max:10000',
            'image' => ['mimes:jpg,png,jpeg', 'max:5048']
        ]);

        $foundSubForum = DB::table('subforums')->where('subforumName', $request->subforum)->get();

        if (count($foundSubForum) === 0) {

            Subforum::create([
                'subforumName' => strip_tags($request->subforum)
            ]);
        }

        $foundSubForumId = DB::table('subforums')->where('subforumName', $request->subforum)->value('id');

        if (!isset($request->image)) {

            Post::create([
                'userId' => Auth::user()->id,
                'subforumId' => $foundSubForumId,
                'title' => strip_tags($request->title),
                'content' => nl2br(strip_tags($request->content))
            ]);
        } else {
            Post::create([
                'userId' => Auth::user()->id,
                'subforumId' => $foundSubForumId,
                'title' => strip_tags($request->title),
                'content' => nl2br(strip_tags($request->content)),
                'imagePath' => $this->storeImage($request)
            ]);
        }

        return redirect(route('index'))->with('success', 'Post created');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //$post = Post::find($id);

        $post = DB::table('posts')
            ->select('posts.id as postId', 'userId', 'subforumId', 'name as userName', 'subforumName', 'title', 'content', 'creationTime', 'imagePath', 'posts.scoreCount', 'users.rating')
            ->where('posts.id', $id)
            ->join('users', 'posts.userId', '=', 'users.id')
            ->join('subforums', 'posts.subforumId', '=', 'subforums.id')
            ->get($id)
            ->first();

        $comments = DB::table('comments')
            ->select('comments.id as commentId', 'userId', 'postId', 'creationTime', 'content', 'scoreCount', 'users.name as userName', 'users.rating')
            ->where('postId', $id)
            ->join('users', 'comments.userId', '=', 'users.id')
            ->get();

        if (Auth::user()) {

            $authUserId = Auth::user()->id;
            return view('show', [
                'post' => $post,
                'comments' => $comments,
                'id' => $authUserId
            ]);
        }

        return view('show', [
            'post' => $post,
            'comments' => $comments
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $subforumId = Post::select('subforumId')->where('id', $id)->value('subforumId');

        //delete all votes for comments under deleteing post
        $postComments = DB::table('comments')->where('postId', $id)->get();

        for ($i = 0; $i < count($postComments); $i++) {
            if ($i < 0) {
                $postCommentId = DB::table('comments')->where('postId', $id)->value('id');
                Vote::where('commentId', $postCommentId)->delete();
            } else
                $postCommentId = DB::table('comments')->where('postId', $id)->skip($i)->value('id');
            Vote::where('commentId', $postCommentId)->delete();
        }

        //delete comments
        Comment::where('postId', $id)->delete();

        //delete post votes
        Vote::where('postId', $id)->delete();

        //then post
        Post::destroy($id);

        //check if aftere post deleted subforum has any other posts, if to -> delete subforum
        $subHasPosts = Post::select('subforumId')->where('subforumId', $subforumId)->get();

        if ($subHasPosts->isEmpty()) {
            Subforum::where('id', $subforumId)->delete();
        }
        return redirect(route('index'))->with('success', 'Post has been deleted');
    }

    public function userPosts($userName)
    {

        $userRating = DB::table('users')
            ->select('rating')
            ->where('name', $userName)
            ->value('rating');

        $posts = DB::table('posts')
            ->select('posts.id', 'userId', 'subforumId', 'name  as author', 'subforumName', 'title', 'content', 'creationTime', 'imagePath', 'posts.scoreCount', 'users.rating', DB::raw('DATEDIFF(CURRENT_DATE(), creationTime) as createdDaysAgo'))
            ->join('users', 'posts.userId', '=', 'users.id')
            ->join('subforums', 'posts.subforumId', '=', 'subforums.id')
            ->where('name', $userName)
            ->having('createdDaysAgo', "<", 7)
            ->orderBy('scoreCount', 'desc')
            ->orderBy('creationTime', 'desc')
            ->paginate(10);

        return view('userposts', [
            'userRating' => $userRating,
            'posts' => $posts,
            'userName' => $userName

        ]);
    }

    public function sortTopByUser($userName)
    {

        $userRating = DB::table('users')
            ->select('rating')
            ->where('name', $userName)
            ->value('rating');

        $posts = DB::table('posts')
            ->select('posts.id', 'userId', 'subforumId', 'name as author', 'subforumName', 'title', 'content', 'creationTime', 'imagePath', 'posts.scoreCount', 'users.rating')
            ->join('users', 'posts.userId', '=', 'users.id')
            ->join('subforums', 'posts.subforumId', '=', 'subforums.id')
            ->where('users.name', $userName)
            ->orderBy('scoreCount', 'desc')
            ->paginate(10);

        return view('userposts', [
            'posts' => $posts,
            'userName' => $userName,
            'userRating' => $userRating
        ]);
    }

    public function sortNewByUser($userName)
    {

        $userRating = DB::table('users')
            ->select('rating')
            ->where('name', $userName)
            ->value('rating');

        $posts = DB::table('posts')
            ->select('posts.id', 'userId', 'subforumId', 'name as author', 'subforumName', 'title', 'content', 'creationTime', 'imagePath', 'posts.scoreCount', 'users.rating')
            ->join('users', 'posts.userId', '=', 'users.id')
            ->join('subforums', 'posts.subforumId', '=', 'subforums.id')
            ->where('users.name', $userName)
            ->orderBy('creationTime', 'desc')
            ->paginate(10);

        return view('userposts', [
            'posts' => $posts,
            'userName' => $userName,
            'userRating' => $userRating
        ]);
    }

    public function subforum($subforumName)
    {
        $subforumNameDB = DB::table('subforums')
            ->select('subforumName')
            ->where('subforumName', $subforumName)
            ->value('subforumName');

        //get score sum
        $subforumScoreSum = DB::select('SELECT SUM(scoreCount) as totalScore FROM `posts` inner join subforums on subforums.id = posts.subforumId where subforumName = ?', [
            $subforumName
        ])[0]->totalScore;

        $posts = DB::table('posts')
            ->select('posts.id', 'userId', 'subforumId', 'name  as author', 'subforumName', 'title', 'content', 'creationTime', 'imagePath', 'posts.scoreCount', 'users.rating', DB::raw('DATEDIFF(CURRENT_DATE(), creationTime) as createdDaysAgo'))
            ->join('users', 'posts.userId', '=', 'users.id')
            ->join('subforums', 'posts.subforumId', '=', 'subforums.id')
            ->where('subforumName', $subforumName)
            ->having('createdDaysAgo', "<", 7)
            ->orderBy('scoreCount', 'desc')
            ->orderBy('creationTime', 'desc')
            ->paginate(10);

        return view('subforum', [
            'posts' => $posts,
            'subforumNameDB' => $subforumNameDB,
            'scoreSum' => $subforumScoreSum
        ]);
    }

    //sort by hotSub
    public function subSortByTop($subforumName)
    {
        $subforumNameDB = DB::table('subforums')
            ->select('subforumName')
            ->where('subforumName', $subforumName)
            ->value('subforumName');

        $subforumScoreSum = DB::select('SELECT SUM(scoreCount) as totalScore FROM `posts` inner join subforums on subforums.id = posts.subforumId where subforumName = ?', [
            $subforumName
        ])[0]->totalScore;

        $posts = DB::table('posts')
            ->select('posts.id', 'userId', 'subforumId', 'name as author', 'subforumName', 'title', 'content', 'creationTime', 'imagePath', 'posts.scoreCount', 'users.rating')
            ->where('subforumName', $subforumName)
            ->join('users', 'posts.userId', '=', 'users.id')
            ->join('subforums', 'posts.subforumId', '=', 'subforums.id')
            ->orderBy('posts.scoreCount', 'desc')
            ->paginate(10);

        return view('subforum', [
            'posts' => $posts,
            'subforumNameDB' => $subforumNameDB,
            'scoreSum' => $subforumScoreSum
        ]);
    }

    public function subSortByNew($subforumName)
    {
        $subforumNameDB = DB::table('subforums')
            ->select('subforumName')
            ->where('subforumName', $subforumName)
            ->value('subforumName');

        $subforumScoreSum = DB::select('SELECT SUM(scoreCount) as totalScore FROM `posts` inner join subforums on subforums.id = posts.subforumId where subforumName = ?', [
            $subforumName
        ])[0]->totalScore;

        $posts = DB::table('posts')
            ->select('posts.id', 'userId', 'subforumId', 'name as author', 'subforumName', 'title', 'content', 'creationTime', 'imagePath', 'posts.scoreCount', 'users.rating')
            ->where('subforumName', $subforumName)
            ->join('users', 'posts.userId', '=', 'users.id')
            ->join('subforums', 'posts.subforumId', '=', 'subforums.id')
            ->orderBy('creationTime', 'desc')
            ->paginate(10);

        return view('subforum', [
            'posts' => $posts,
            'subforumNameDB' => $subforumNameDB,
            'scoreSum' => $subforumScoreSum
        ]);
    }

    // NEW votepost()
    public function votepost($postId)
    {
        $loggedUserId = Auth::user()->id;
        $existingVote = Vote::where('postId', $postId)->where('userId', $loggedUserId)->first();
        $userId = DB::table('posts')
            ->select('users.id as userId', 'posts.id as postId')
            ->join('users', 'posts.userId', '=', 'users.id')
            ->where('posts.id', $postId)
            ->value('userId');

        if (!$existingVote) {

            Vote::create([
                'postId' => $postId,
                'userId' => $loggedUserId
            ]);

            DB::update('UPDATE  users SET rating = rating + 1 WHERE id = ?', [
                $userId
            ]);

            $postScore = Post::find($postId);
            $postScore->scoreCount += 1;
            $postScore->save();
        } else {

            Vote::where('postId', $postId)->where('userId', $loggedUserId)->delete();

            DB::update('UPDATE  users SET rating = rating - 1 WHERE id = ?', [
                $userId
            ]);

            $postScore = Post::find($postId);
            $postScore->scoreCount -= 1;
            $postScore->save();
        }
    }

    //function for file upload
    private function storeImage($request)
    {
        $newImageName = uniqid() . '-' . $request->image->getClientOriginalName(); //  . '.' . $request->image->extension()

        $request->image->move(public_path('img'), $newImageName);

        return $newImageName;
    }
}
