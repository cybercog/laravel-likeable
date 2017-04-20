![cog-laravel-likeable-3](https://cloud.githubusercontent.com/assets/1849174/21738599/f29a5428-d498-11e6-98b3-51e6511e2d4c.png)

<p align="center">
<a href="https://travis-ci.org/cybercog/laravel-likeable"><img src="https://img.shields.io/travis/cybercog/laravel-likeable/master.svg?style=flat-square" alt="Build Status"></a>
<a href="https://styleci.io/repos/67549571"><img src="https://styleci.io/repos/67549571/shield" alt="StyleCI"></a>
<a href="https://github.com/cybercog/laravel-likeable/releases"><img src="https://img.shields.io/github/release/cybercog/laravel-likeable.svg?style=flat-square" alt="Releases"></a>
<a href="https://github.com/cybercog/laravel-likeable/blob/master/LICENSE"><img src="https://img.shields.io/github/license/cybercog/laravel-likeable.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

Laravel Likeable simplify management of Eloquent model's likes & dislikes. Make any model `likeable` & `dislikeable` in a minutes!

## Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
    - [Prepare likeable model](#prepare-likeable-model)
    - [Available methods](#available-methods)
    - [Scopes](#scopes)
    - [Events](#events)
    - [Console commands](#console-commands)
- [Extending](#extending)
- [Change log](#change-log)
- [Contributing](#contributing)
- [Testing](#testing)
- [Security](#security)
- [Credits](#credits)
- [Alternatives](#alternatives)
- [License](#license)
- [About CyberCog](#about-cybercog)

## Features

- Designed to work with Laravel Eloquent models.
- Using contracts to keep high customization capabilities.
- Using traits to get functionality out of the box.
- Most part of the the logic is handled by the `LikeableService`.
- Has Artisan command `likeable:recount {model?} {type?}` to re-fetch likes counters.
- Likeable model can has Likes and Dislikes.
- Likes and Dislikes for one model are mutually exclusive.
- Get Likeable models ordered by likes count.
- Events for `like`, `unlike`, `dislike`, `undislike` methods.
- Covered with unit tests.

## Installation

First, pull in the package through Composer.

```sh
composer require cybercog/laravel-likeable
```

And then include the service provider within `app/config/app.php`.

```php
'providers' => [
    Cog\Likeable\Providers\LikeableServiceProvider::class,
],
```

At last you need to publish and run database migrations.

```sh
php artisan vendor:publish --provider="Cog\Likeable\Providers\LikeableServiceProvider" --tag=migrations
php artisan migrate
```

## Usage

### Prepare likeable model

Use `HasLikes` contract in model which will get likes behavior and implement it or just use `HasLikes` trait. 

```php
use Cog\Likeable\Contracts\HasLikes as HasLikesContract;
use Cog\Likeable\Traits\HasLikes;
use Illuminate\Database\Eloquent\Model;

class Article extends Model implements HasLikesContract {
    use HasLikes;
}
```

### Available methods

#### Likes

##### Like model

```php
$article->like(); // current user
$article->like($user->id);
```

##### Remove like mark from model

```php
$article->unlike(); // current user
$article->unlike($user->id);
```

##### Toggle like mark of model

```php
$article->likeToggle(); // current user
$article->likeToggle($user->id);
```

##### Get model likes count

```php
$article->likesCount;
```

##### Get model likes counter

```php
$article->likesCounter;
```

##### Get likes relation

```php
$article->likes();
```

##### Get iterable `Illuminate\Database\Eloquent\Collection` of existing model likes

```php
$article->likes;
```

##### Boolean check if user liked model

```php
$article->liked; // current user
$article->liked(); // current user
$article->liked($user->id);
```

##### Delete all likes for model

```php
$article->removeLikes();
```

#### Dislikes

##### Dislike model

```php
$article->dislike(); // current user
$article->dislike($user->id);
```

##### Remove dislike mark from model

```php
$article->undislike(); // current user
$article->undislike($user->id);
```

##### Toggle dislike mark of model

```php
$article->dislikeToggle(); // current user
$article->dislikeToggle($user->id);
```

##### Get model dislikes count

```php
$article->dislikesCount;
```

##### Get model dislikes counter

```php
$article->dislikesCounter;
```

##### Get dislikes relation

```php
$article->dislikes();
```

##### Get iterable `Illuminate\Database\Eloquent\Collection` of existing model dislikes

```php
$article->dislikes;
```

##### Boolean check if user disliked model

```php
$article->disliked; // current user
$article->disliked(); // current user
$article->disliked($user->id);
```

##### Delete all dislikes for model

```php
$article->removeDislikes();
```

#### Likes and Dislikes

##### Get difference between likes and dislikes

```php
$article->likesDiffDislikesCount;
```

##### Get likes and dislikes relation

```php
$article->likesAndDislikes();
```

##### Get iterable `Illuminate\Database\Eloquent\Collection` of existing model likes and dislikes

```php
$article->likesAndDislikes;
```

### Scopes

##### Find all articles liked by user

```php
Article::whereLikedBy($user->id)
    ->with('likesCounter') // Allow eager load (optional)
    ->get();
```

##### Find all articles disliked by user

```php
Article::whereDislikedBy($user->id)
    ->with('dislikesCounter') // Allow eager load (optional)
    ->get();
```

##### Fetch Likeable models by likes count

```php
$sortedArticles = Article::orderByLikesCount()->get();
$sortedArticles = Article::orderByLikesCount('asc')->get();
```

*Uses `desc` as default order direction.*

##### Fetch Likeable models by dislikes count

```php
$sortedArticles = Article::orderByDislikesCount()->get();
$sortedArticles = Article::orderByDislikesCount('asc')->get();
```

*Uses `desc` as default order direction.*

### Events

On each like added `\Cog\Likeable\Events\ModelWasLiked` event is fired.

On each like removed `\Cog\Likeable\Events\ModelWasUnliked` event is fired.

On each dislike added `\Cog\Likeable\Events\ModelWasDisliked` event is fired.

On each dislike removed `\Cog\Likeable\Events\ModelWasUndisliked` event is fired.

### Console commands

##### Recount likes and dislikes of all model types

```sh
likeable:recount
```

##### Recount likes and dislikes of concrete model type (using morph map alias)

```sh
likeable:recount --model="article"
```

##### Recount likes and dislikes of concrete model type (using fully qualified class name)

```sh
likeable:recount --model="App\Models\Article"
```

##### Recount only likes of all model types

```sh
likeable:recount --type="like"
```

##### Recount only likes of concrete model type (using morph map alias)

```sh
likeable:recount --model="article" --type="like"
```

##### Recount only likes of concrete model type (using fully qualified class name)

```sh
likeable:recount --model="App\Models\Article" --type="like"
```

##### Recount only dislikes of all model types

```sh
likeable:recount --type="dislike"
```

##### Recount only dislikes of concrete model type (using morph map alias)

```sh
likeable:recount --model="article" --type="dislike"
```

##### Recount only dislikes of concrete model type (using fully qualified class name)

```shell
likeable:recount --model="App\Models\Article" --type="dislike"
```

## Extending

You can override core classes of package with your own implementations:

- `Models\Like`
- `Models\LikeCounter`
- `Services\LikeableService`

*Note: Don't forget that all custom models must implement original models interfaces.*

To make it you should use container [binding interfaces to implementations](https://laravel.com/docs/master/container#binding-interfaces-to-implementations) in your application service providers.

##### Use model class own implementation

```php
$this->app->bind(
    \Cog\Likeable\Contracts\Like::class,
    \App\Models\CustomLike::class
);
```

##### Use service class own implementation

```php
$this->app->singleton(
    \Cog\Likeable\Contracts\LikeableService::class,
    \App\Services\CustomService::class
);
```

After that your `CustomLike` and `CustomService` classes will be instantiable with helper method `app()`.

```php
$model = app(\Cog\Likeable\Contracts\Like::class);
$service = app(\Cog\Likeable\Contracts\LikeableService::class);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Testing

You can run the tests with:

```sh
vendor/bin/phpunit
```

## Security

If you discover any security related issues, please email oss@cybercog.su instead of using the issue tracker.

## Credits

- [Anton Komarev](https://github.com/a-komarev)
- [All Contributors](../../contributors)

## Alternatives

- [rtconner/laravel-likeable](https://github.com/rtconner/laravel-likeable)
- [faustbrian/laravel-likeable](https://github.com/faustbrian/Laravel-Likeable)
- [sukohi/evaluation](https://github.com/SUKOHI/Evaluation)
- [zvermafia/lavoter](https://github.com/zvermafia/lavoter)

*Feel free to add more alternatives as Pull Request.*

## License

- `Laravel Likeable` package is open-sourced software licensed under the [MIT license](LICENSE).

## About CyberCog

[CyberCog](http://www.cybercog.ru) is a Social Unity of enthusiasts. Research best solutions in product & software development is our passion.

![cybercog-logo](https://cloud.githubusercontent.com/assets/1849174/18418932/e9edb390-7860-11e6-8a43-aa3fad524664.png)
