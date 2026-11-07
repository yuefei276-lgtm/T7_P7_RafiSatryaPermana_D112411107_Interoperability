<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /posts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Post::orderBy("id", "DESC")->paginate(2)->toArray();

    $response = [
        "total_count" => $posts["total"],
        "limit"       => $posts["per_page"],
        "pagination"  => [
            "next_page"    => $posts["next_page_url"],
            "current_page" => $posts["current_page"]
        ],
        "data"        => $posts["data"],
    ];

    return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     * POST /posts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $input = $request->all();

    $validationRules = [
        'title'   => 'required|min:5',
        'content' => 'required|min:10',
        'status'  => 'required|in:draft,published',
        'user_id' => 'required|exists:users,id'
    ];

    $validator = \Validator::make($input, $validationRules);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $post = Post::create($input);
    return response()->json($post, 200);
    }

    /**
     * Display the specified resource.
     * GET /post/{postId}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $postId
     * @param  mixed  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $postId = null, $id = null)
    {
        $targetId = $postId ?? $id;
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $post = Post::find($targetId);

            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }

            if ($acceptHeader === 'application/json') {
                return response()->json($post, 200);
            } else {
                $xml = new \SimpleXMLElement('<post/>');
                $xml->addChild('id', $post->id);
                $xml->addChild('title', htmlspecialchars($post->title));
                $xml->addChild('status', $post->status);
                $xml->addChild('content', htmlspecialchars($post->content));
                $xml->addChild('user_id', $post->user_id);
                $xml->addChild('created_at', (string) $post->created_at);
                $xml->addChild('updated_at', (string) $post->updated_at);

                return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /post/{postId}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $postId
     * @param  mixed  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

    $post = Post::find($id);
    if (!$post) {
        abort(404);
    }

    $validationRules = [
        'title'   => 'required|min:5',
        'content' => 'required|min:10',
        'status'  => 'required|in:draft,published',
        'user_id' => 'required|exists:users,id'
    ];

    $validator = \Validator::make($input, $validationRules);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $post->fill($input);
    $post->save();

    return response()->json($post, 200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /post/{postId}
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $postId
     * @param  mixed  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $postId = null, $id = null)
    {
        $targetId = $postId ?? $id;
        $acceptHeader = $request->header('Accept');
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $post = Post::find($targetId);
            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }
            $post->delete();
            $responseData = ['message' => 'deleted successfully', 'post_id' => $targetId];
            if ($acceptHeader === 'application/json') {
                return response()->json($responseData, 200);
            } else {
                $xml = new \SimpleXMLElement('<response/>');
                $xml->addChild('message', 'deleted successfully');
                $xml->addChild('post_id', $targetId);
                return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
            }
        } else {
            return response('Not Acceptable!', 406);
        }
    }

    /**
     * Display comments for the post.
     * GET /post/{postId}/comments
     *
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function comments($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            abort(404);
        }

        return response()->json($post->comments()->with('user:id,name,email')->get(), 200);
    }

    /**
     * Store a comment for the post.
     * POST /post/{postId}/comments
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $postId
     * @return \Illuminate\Http\Response
     */
    public function commentStore(Request $request, $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            abort(404);
        }

        $comment = $post->comments()->create($request->all());

        return response()->json($comment, 201);
    }

    /**
     * Remove a comment for the post.
     * DELETE /post/{postId}/comment/{commentId}
     *
     * @param  int  $postId
     * @param  int  $commentId
     * @return \Illuminate\Http\Response
     */
    public function commentDestroy($postId, $commentId)
    {
        $comment = Comment::where('post_id', $postId)->where('id', $commentId)->first();

        if (!$comment) {
            abort(404);
        }

        $comment->delete();

        return response()->json(['message' => 'deleted successfully', 'comment_id' => $commentId], 200);
    }
}
