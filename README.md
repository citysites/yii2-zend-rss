yii2-zend-rss
=========
This extension is a Yii 2 wrapper of [Zend\Feed](http://framework.zend.com/manual/current/en/modules/zend.feed.introduction.html).

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require shaposhnikoff/yii2-zend-rss
```

or add

```
"shaposhnikoff/yii2-zend-rss": "dev-master"
```

to the require section of your `composer.json` file.

Configuration
-------------

Add feed component in config file

```php
            'components' => [
                'feed' => [
                    'class' => 'yii\feed\FeedDriver',
                ],
            ]
```

Simple usage
-----

__Read Rss feed:__

```php
$feed = Yii::$app->feed->reader()->import('http://example.com/feed.rss');
```

This will get RSS feed, parse it and return feed object.
For more details you can read the official Zend-feed extention documentation:
http://framework.zend.com/manual/2.2/en/modules/zend.feed.reader.html

__Create Rss feed:__

Create action Rss in controller

```php
    public function actionRss()
    {
    
        $feed = Yii::$app->feed->writer();
    
        $feed->setTitle(Yii::$app->params['title']);
        $feed->setLink('http://example.com');
        $feed->setFeedLink('http://example.com/rss', 'rss');
        $feed->setDescription(Yii::t('app', 'Recent headlines'));
        $feed->setGenerator('http://example.com/rss');
        $feed->setDateModified(time());
        /**
         * Add one or more entries. Note that entries must
         * be manually added once created.
         */
        $posts = Post::find()->orderBy('id DESC')->limit(20)->all();
        foreach ($posts as $post) {
            $entry = $feed->createEntry();
            $entry->setTitle($post->title);
            $entry->setLink(Yii::$app->urlManager->createAbsoluteUrl('/post/view', ['id' => $post->id]));
            $entry->setDateModified(intval($post->created));
            $entry->setDateCreated(intval($post->created));
            $entry->setContent(
                $post->content
            );
            $entry->setEnclosure(
                [
                    'uri' => $post->image,
                    'type' => 'image/jpeg',
                    'length' => filesize(Yii::getAlias('@webroot') . $post->image)
                ]
            );
            $feed->addEntry($entry);
        }
        /**
         * Render the resulting feed to Atom 1.0 and assign to $out.
         * You can substitute "atom" with "rss" to generate an RSS 2.0 feed.
         */
        $out = $feed->export('rss');
        header('Content-type: text/xml');
        echo $out;
        die();
    }
```

Then it's better to cache it with cache component:

```php
public function behaviors()
    {
        return [
            ...
            'cache' => [
                'only' => ['rss'],
                'class' => PageCache::className(),
                'duration' => 0,
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT max(time_updated) as max FROM tbl_post',

                ],
            ]
        ];
    }
```

Take a look at Zend-feed writer official documentation for more advanced usage of Zend-feed
http://framework.zend.com/manual/2.2/en/modules/zend.feed.writer.html
