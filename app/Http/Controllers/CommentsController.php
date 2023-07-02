<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{

    public function create(Request $request, $id)
    {

        $request->validate([
            'commentContent' => 'required|max:1000'
        ]);

        Comment::create([
            'userId' => Auth::user()->id,
            'postId' => $id,
            'content' => nl2br(strip_tags($request->commentContent))
        ]);

        return redirect('/post/' . $id);
    }

    public function votecomment($commentId)
    {


        $loggedUserId = Auth::user()->id;
        $existingVote = Vote::where('commentId', $commentId)->where('userId', $loggedUserId)->first();

        $userId = DB::table('comments')
            ->select('users.id as userId', 'comments.id as commentId')
            ->join('users', 'comments.userId', '=', 'users.id')
            ->where('comments.id', $commentId)
            ->value('userId');

        if (!$existingVote) {

            Vote::create([
                'commentId' => $commentId,
                'userId' => $loggedUserId
            ]);

            //+1 to user's rating
            DB::update('UPDATE  users SET rating = rating + 1 WHERE id = ?', [
                $userId
            ]);

            $commentScore = Comment::find($commentId);
            $commentScore->scoreCount += 1;
            $commentScore->save();
        } else {

            Vote::where('commentId', $commentId)->where('userId', $loggedUserId)->delete();

            //-1 from user's rating
            DB::update('UPDATE  users SET rating = rating - 1 WHERE id = ?', [
                $userId
            ]);

            $commentScore = Comment::find($commentId);
            $commentScore->scoreCount -= 1;
            $commentScore->save();
        }

        //return redirect('/post/' . 16);
    }

    public function delete($id)
    {
        //first delete votes
        Vote::where('commentId', $id)->delete();

        //then delete comments
        Comment::destroy($id);
        return redirect()->back()->with('success', 'Comment has been deleted');
    }
}
