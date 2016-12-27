# Changelog

All notable changes to `laravel-likeable` will be documented in this file.

## 3.0.0 - 2016-09-12

`LikeCounter` entity renamed to `LikersCounter` to be more intuitive and provide more extensibility for package.

- Renamed database table `like_counter` to `liker_counter`
- Renamed `LikeCounter` to `LikerCounter` model
- Renamed `HasLikes` trait methods:
    - `likesCounter()` to `likersCounter()`
    - `dislikesCounter()` to `dislikersCounter()`
    - `getLikesCountAttribute()` to `getLikersCountAttribute()`
    - `getDislikesCountAttribute()` to `getDislikersCountAttribute()`
    - `getLikesDiffDislikesCountAttribute()` to `getLikersDiffDislikersCountAttribute()`
- Renamed `LikeableService` methods:
    - `decrementLikesCount()` to `decrementLikersCount()`
    - `decrementDislikesCount()` to `decrementDislikersCount()`
    - `incrementLikesCount()` to `incrementLikersCount()`
    - `incrementDislikesCount()` to `incrementDislikersCount()`
    - `removeLikeCountersOfType()` to `removeLikerCountersOfType()`
    - `fetchLikesCounters()` to `fetchLikersCounters()`

## 2.0.0 - 2016-09-11

- Renamed `FollowableService` methods to follow code style consistency:
    - `incrementLikeCount()` to `incrementLikesCount()`
    - `decrementLikeCount()` to `decrementLikesCount()`
    - `decrementDislikeCount()` to `decrementDislikesCount()`
    - `incrementDislikeCount()` to `incrementDislikesCount()`

## 1.1.2 - 2016-09-07

- Fix enum like types

## 1.1.1 - 2016-09-07

- Fix likeable enums database default value

## 1.1.0 - 2016-09-07

- Renamed `HasLikes` trait methods to follow code style consistency:
    - `likeCounter()` to `likesCounter()`
    - `dislikeCounter()` to `dislikesCounter()`

## 1.0.0 - 2016-09-06

- Initial release
