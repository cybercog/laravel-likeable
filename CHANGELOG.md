# Changelog

All notable changes to `laravel-likeable` will be documented in this file.

## [3.1.0] - 2017-12-28

### Changed

- Checks if model liked by user will try to search in eager loaded relations first

## [3.0.0] - 2017-08-24

### Added

- Laravel 5.5 support
- Laravel Package Auto-Discovery support
- Eloquent related method `getKey` & `getMorphClass` added to `Cog\Likeable\Contracts\Likeable` contract
- `collectLikers`, `collectDislikers` & `scopeOrderByDislikesCount` methods added to `Cog\Likeable\Contracts\Likeable` contract
- `collectLikersOf` & `collectDislikersOf` methods to `Cog\Likeable\Contracts\LikeableService` contract

### Changed

- `Cog\Likeable\Contracts` contract renamed to `Cog\Likeable\Contracts\Likeable`
- `Cog\Likeable\Traits\HasLikes` trait renamed to `Cog\Likeable\Traits\Likeable`

## [2.2.5] - 2017-07-10

### Fixed

- Event observing of custom `Like` model (#18)

## [2.2.4] - 2017-04-20

### Added

- `orderByDislikesCount` scope added to `HasLikes` trait.
- `scopeOrderByLikesCount` method to `LikeableService`.

### Fixed

- `orderByLikesCount` count only likes now.

## [2.2.3] - 2017-04-20

### Fixed

`orderByLikesCount` work in MySQL databases.

## [2.2.2] - 2017-04-09

### Fixed

- `orderByLikesCount` returns models without likes too.

## [2.2.1] - 2017-04-09

### Fixed

- `orderByLikesCount` database query fixed.

## [2.2.0] - 2017-04-09

### Added

- `Article::orderByLikesCount('asc')` scope for the model. Uses `desc` as default order direction.

## [2.1.0] - 2017-02-20

### Added

- Laravel 5.4 support.

## [2.0.1] - 2017-01-11

- Removed unused properties in `LikeObserver` (#12)
- Foreign key in migration commented out (#11)

## [2.0.0] - 2016-09-11

- Renamed `FollowableService` methods to follow code style consistency:
    - `incrementLikeCount()` to `incrementLikesCount()`
    - `decrementLikeCount()` to `decrementLikesCount()`
    - `decrementDislikeCount()` to `decrementDislikesCount()`
    - `incrementDislikeCount()` to `incrementDislikesCount()`

## [1.1.2] - 2016-09-07

- Fix enum like types

## [1.1.1] - 2016-09-07

- Fix likeable enums database default value

## [1.1.0] - 2016-09-07

- Renamed `HasLikes` trait methods to follow code style consistency:
    - `likeCounter()` to `likesCounter()`
    - `dislikeCounter()` to `dislikesCounter()`

## 1.0.0 - 2016-09-06

- Initial release

[3.1.0]: https://github.com/cybercog/laravel-likeable/compare/3.0.0...3.1.0
[3.0.0]: https://github.com/cybercog/laravel-likeable/compare/2.2.5...3.0.0
[2.2.5]: https://github.com/cybercog/laravel-likeable/compare/2.2.4...2.2.5
[2.2.4]: https://github.com/cybercog/laravel-likeable/compare/2.2.3...2.2.4
[2.2.3]: https://github.com/cybercog/laravel-likeable/compare/2.2.2...2.2.3
[2.2.2]: https://github.com/cybercog/laravel-likeable/compare/2.2.1...2.2.2
[2.2.1]: https://github.com/cybercog/laravel-likeable/compare/2.2.0...2.2.1
[2.2.0]: https://github.com/cybercog/laravel-likeable/compare/2.1.0...2.2.0
[2.1.0]: https://github.com/cybercog/laravel-likeable/compare/2.0.1...2.1.0
[2.0.1]: https://github.com/cybercog/laravel-likeable/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/cybercog/laravel-likeable/compare/1.1.2...2.0.0
[1.1.2]: https://github.com/cybercog/laravel-likeable/compare/1.1.1...1.1.2
[1.1.1]: https://github.com/cybercog/laravel-likeable/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/cybercog/laravel-likeable/compare/1.0.0...1.1.0
