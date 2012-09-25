hack4europe3d
=============

Hack4Europe 3D exhibition app

# Developers

* Brendan Flynn
* Simon Kenny
* Mark Reilly
* Richard Cyganiak

# Concept

* 3D gallery builder
* Create your own gallery and share it
* Potential: Place "camera rails" for a non-interactive tour
** Could be running in a banner on a website
* Potential: mobile app
* Potential: buy prints

# Planning

## Minimal demo

* Requires: Click handling
* Requires: Work around same-origin restriction???
* Click on wall
* Pick image from Europeana
* Image is placed on wall

## Images as links

* Requires: UI
* "Add image" button
* If you're not in "add image" mode and click on an existing image, it's a link

## Minimal loading and saving

* Requires: UI
* Save button
* Brings up a form where you enter title, description, your name, email, id
* Homepage that lists existing saved exhibitions, or to start a new one
* Existing ones are accessable at /exhibition/{id}

## Room building

* Requires: Click handling
* Requires: UI
* Enable "wall editing tool"
* Highlight wall/floor/ceiling under mouse
* Clicking toggles

## Proper image building

* Requires: Small button
* Requires: UI
* Add button for modifying images
* You're also in "modifying images mode" after adding an image
* Show "remove", "reposition", "make larger", "make smaller" buttons

## Show image title and details

* Requires: Small button
* Requires: UI

# Similar stuff / inspiration

* [Three.js 3D maze](http://www.demonixis.net/lab/index.php?p=threejs-maze3d)
