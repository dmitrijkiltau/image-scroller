# WordPress Image Scroller

A simple image scroller shortcode plugin for [WordPress](https://wordpress.org/).

## Usage

```
[image_scroller ids="" duration="" links="" target="" reverse="" pausable=""]
```

| param     | type    | description                                                         |
|-----------|---------|---------------------------------------------------------------------|
| ids*      | array   | Image ids from media; separated by a comma without following space. |
| duration* | string  | Any valid time, e.g. `3s` or `1500ms`.                              |
| links     | array   | Urls separated by a comma without following space.                  |
| target    | string  | Any valid HTML target (used for all links), e.g. `_blank`.          |
| reverse   | boolean | `true` if scroll direction should be from left to right instead.    |
| pausable  | boolean | `true` if animation should pause on mouseover.                      |

_* You need to have at least `ids` and `duration` set for it to work._
