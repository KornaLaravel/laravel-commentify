<?php

namespace Usamamuneerchaudhary\Commentify\Scopes;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Usamamuneerchaudhary\Commentify\Models\CommentLike;
use Usamamuneerchaudhary\Commentify\Models\User;

trait HasLikes
{
    /**
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    /**
     * @return bool
     */
    public function isLiked(): bool
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        if (auth()->user()) {
            return $this->likes()->where('user_id', auth()->user()->id)->exists();
        }

        if ($ip && $userAgent) {
            return $this->likes()->forIp($ip)->forUserAgent($userAgent)->exists();
        }

        return false;
    }

    /**
     * @return bool
     */
    public function removeLike(): bool
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        if (auth()->user()) {
            return $this->likes()->where('user_id', auth()->user()->id)->where('comment_id', $this->id)->delete();
        }

        if ($ip && $userAgent) {
            return $this->likes()->forIp($ip)->forUserAgent($userAgent)->delete();
        }

        return false;
    }
}
