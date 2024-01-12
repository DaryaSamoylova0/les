<?php

namespace http\Actions\Likes;

use http\Actions\ActionInterface;
use http\ErrorResponse;
use http\Request;
use http\Response;
use http\SuccessfullResponse;
use my\Exceptions\CommentLikeNotFoundException;
use my\Exceptions\HttpException;
use my\Model\CommentLike;
use my\Model\UUID;
use my\Repositories\CommentLikeRepository;

class GetByUuidCommentLikes implements ActionInterface
{
    public function __construct(
        private CommentLikeRepository $commentLikeRepository
    ) { }

    public function handle(Request $request): Response
    {
        try {
            $commentUuid = $request->query('uuid');
        } catch (HttpException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        try {
            $commentLikes = $this->commentLikeRepository->getByCommentUuid(new UUID($commentUuid));
        } catch (CommentLikeNotFoundException $ex) {
            return new ErrorResponse($ex->getMessage());
        }

        $likesData = array_map(function (CommentLike $like) {
            return [
                'uuid' => (string)$like->getUuid(),
                'user_uuid' => (string)$like->getUserUuid()
            ];
        }, $commentLikes);

        return new SuccessfullResponse([
            'comment' => $commentUuid,
            'likes' => $likesData
        ]);
    }
}