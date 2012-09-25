var canvasId = 'lesson10-canvas';

var cameraPosition = vec3.create([0, 0, 0]);
var mouseVector = vec3.create([0, 0, 0]);
var mouseHit = null;
var clickPos = vec3.create([0, 0, 0]);

var clickPosTexture;
var clickPosVertexPosBuffer = null;
var clickPosVertexTextureCoordBuffer = null;

var buildHandlers = {
    clickFloor: function(gridX, gridZ, localX, localY) {
        cubeMap[gridZ][gridX] = 0;
    },
    clickWall: function(gridX, gridZ, direction, localX, localY) {
        var dx = dy = 0;
        if (direction == 'N') {
            dy = -1;
        } else if (direction == 'S') {
            dy = +1;
        } else if (direction == 'W') {
            dx = -1;
        } else if (direction == 'E') {
            dx = +1;
        }
        cubeMap[gridZ + dy][gridX + dx] = 1;
    }
};

var placePicHandlers = {
    clickWall: function(gridX, gridZ, direction, localX, localY) {
    }
}

var handlers = buildHandlers;

function initInteraction() {
    initClickPos();
    var canvas = document.getElementById(canvasId);
    canvas.onclick = function(event) {
//        clickPos = vec3.create(mouseVector);
//        vec3.add(clickPos, cameraPosition);
        if (mouseHit != null) {
            if (mouseHit[3] == 'C') {
                if (handlers.clickCeiling) {
                    handlers.clickCeiling(mouseHit[1], mouseHit[2], mouseHit[4], mouseHit[5]);
                }
            } else if (mouseHit[3] == 'F') {
                if (handlers.clickFloor) {
                    handlers.clickFloor(mouseHit[1], mouseHit[2], mouseHit[4], mouseHit[5]);
                }
            } else {
                if (handlers.clickWall) {
                    handlers.clickWall(mouseHit[1], mouseHit[2], mouseHit[3], mouseHit[4], mouseHit[5]);
                }
            }
        }
    };
    canvas.onmousemove = function(event) {
        var screenX = event.clientX - getElementOffset(canvas).left;
        var screenY = event.clientY - getElementOffset(canvas).top;
        mouseVector = getMouseVector(screenX, screenY);
    };
}

function initClickPos() {
    clickPosTexture = gl.createTexture();
    clickPosTexture.image = new Image();
    clickPosTexture.image.onload = function () {
        handleLoadedTexture(clickPosTexture)
    }
    clickPosTexture.image.src = "images/floor_wood_1_256.gif";

    var buffer = [0, 3, -22, 1, 3, -22, 0, 4, -22];
    clickPosVertexPosBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, clickPosVertexPosBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(buffer), gl.DYNAMIC_DRAW);
    clickPosVertexPosBuffer.itemSize = 3;
    clickPosVertexPosBuffer.numItems = 3;

    var buffer = [0, 0, 1, 0, 0, 1];
    clickPosVertexTextureCoordBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, clickPosVertexTextureCoordBuffer);
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(buffer), gl.STATIC_DRAW);
    clickPosVertexTextureCoordBuffer.itemSize = 2;
    clickPosVertexTextureCoordBuffer.numItems = 3;
}

function getMouseHit() {
    // What is the mouse pointing at?
    var ceilingHit = null;
    var floorHit = null;
    var verticalWallHit = null;
    var horizontalWallHit = null;
    // Check for hit with the floor
    if (cameraPosition[1] < ceilingHeight && mouseVector[1] > 0) {
        // will hit floor eventually
        var camToCeiling = ceilingHeight - cameraPosition[1];
        var ceilingDist = camToCeiling / mouseVector[1];
        var hitX = mouseVector[0] / mouseVector[1] * camToCeiling + cameraPosition[0];
        var hitZ = mouseVector[2] / mouseVector[1] * camToCeiling + cameraPosition[2];
        var gridX = toGrid(hitX);
        var gridZ = toGrid(hitZ);
        if (gridX >= 0 && gridZ >= 0 && gridX < mapWidth && gridZ < mapHeight) {
            ceilingHit = [ceilingDist, gridX, gridZ, 'C', hitX - toWorld(gridX), hitZ - toWorld(gridZ)];
        }
    }
    // Check for hit with the floor
    if (cameraPosition[1] > 0 && mouseVector[1] < 0) {
        // will hit floor eventually
        var camToFloor = -cameraPosition[1];
        var floorDist = camToFloor / mouseVector[1];
        var hitX = mouseVector[0] / mouseVector[1] * camToFloor + cameraPosition[0];
        var hitZ = mouseVector[2] / mouseVector[1] * camToFloor + cameraPosition[2];
        var gridX = toGrid(hitX);
        var gridZ = toGrid(hitZ);
        if (gridX >= 0 && gridZ >= 0 && gridX < mapWidth && gridZ < mapHeight) {
            floorHit = [floorDist, gridX, gridZ, 'F', hitX - toWorld(gridX), hitZ - toWorld(gridZ)];
        }
    }
    // Check for hits with east-west walls
    if (!isWall(toGrid(cameraPosition[0]), toGrid(cameraPosition[2])) && mouseVector[0] != 0) {
        // will hit a east-west (horizontal) wall eventually
        var gridX = toGrid(cameraPosition[0]);
        if (mouseVector[0] > 0) {
            var stepX = 1;
            var gridOffsetX = 1;
            var wall = 'E';
        } else {
            var stepX = -1;
            var gridOffsetX = 0;
            var wall = 'W';
        }
        while (gridX > 0 && gridX < mapWidth - 1) {
            var intersectionX = toWorld(gridX + gridOffsetX);
            var camToGrid = intersectionX - cameraPosition[0];
            var intersectionDist = camToGrid / mouseVector[0];
            var intersectionZ = mouseVector[2] / mouseVector[0] * camToGrid + cameraPosition[2];
            var intersectionY = mouseVector[1] / mouseVector[0] * camToGrid + cameraPosition[1];
            var gridZ = toGrid(intersectionZ);
            var localX = intersectionZ - toWorld(gridZ);
            var localY = intersectionY - toWorld(toGrid(intersectionY));
            if (gridZ < 0 || gridZ >= mapHeight) {
                break;
            }
            if (isWall(gridX + stepX, gridZ)) {
                verticalWallHit = [intersectionDist, gridX, gridZ, wall, localX, localY];
                break;
            }
            gridX += stepX;
        }
    }
    // Check for hits with north-south walls
    if (!isWall(toGrid(cameraPosition[0]), toGrid(cameraPosition[2])) && mouseVector[2] != 0) {
        // will hit a north-south (vertical) wall eventually
        var gridZ = toGrid(cameraPosition[2]);
        if (mouseVector[2] > 0) {
            var stepZ = 1;
            var gridOffsetZ = 1;
            var wall = 'S';
        } else {
            var stepZ = -1;
            var gridOffsetZ = 0;
            var wall = 'N';
        }
        while (gridZ > 0 && gridZ < mapHeight - 1) {
            var intersectionZ = toWorld(gridZ + gridOffsetZ);
            var camToGrid = intersectionZ - cameraPosition[2];
            var intersectionDist = camToGrid / mouseVector[2];
            var intersectionX = mouseVector[0] / mouseVector[2] * camToGrid + cameraPosition[0];
            var intersectionY = mouseVector[1] / mouseVector[2] * camToGrid + cameraPosition[1];
            var gridX = toGrid(intersectionX);
            var localX = intersectionX - toWorld(gridX);
            var localY = intersectionY - toWorld(toGrid(intersectionY));
            if (gridX < 0 || gridX > mapWidth) {
                break;
            }
            if (isWall(gridX, gridZ + stepZ)) {
                horizontalWallHit = [intersectionDist, gridX, gridZ, wall, localX, localY];
                break;
            }
            gridZ += stepZ;
        }
    }
    // Check which of the hits is closest to the camera
    var mouseHit = [Number.MAX_VALUE];
    if (ceilingHit && ceilingHit[0] < mouseHit[0]) {
        mouseHit = ceilingHit;
    }
    if (floorHit && floorHit[0] < mouseHit[0]) {
        mouseHit = floorHit;
    }
    if (verticalWallHit && verticalWallHit[0] < mouseHit[0]) {
        mouseHit = verticalWallHit;
    }
    if (horizontalWallHit && horizontalWallHit[0] < mouseHit[0]) {
        mouseHit = horizontalWallHit;
    }
    if (mouseHit[0] == Number.MAX_VALUE) {
        document.getElementById('mousepos').childNodes[0].nodeValue = 'nothing';
        return null;
    }

    if (mouseHit[3] == 'F') {
        var s = 'Floor at (';
    } else if (mouseHit[3] == 'C') {
        var s = 'Ceiling at (';
    } else {
        var s = mouseHit[3] + ' wall at (';
    }
    s += mouseHit[1] + ',' + mouseHit[2] + ') with local coords (';
    s += (Math.round(mouseHit[4] * 10) / 10) + ',' + (Math.round(mouseHit[5] * 10) / 10) + '), distance ' + Math.round(mouseHit[0] * 1000) / 1000;
    document.getElementById('mousepos').childNodes[0].nodeValue = s;
    return mouseHit;
}

function animate2() {
    cameraPosition = vec3.create([xPos, yPos, zPos]);
    mouseHit = getMouseHit();
}

function toGrid(value) {
    return Math.floor(value / wallWidth);
}

function toWorld(value) {
    return value * wallWidth;
}

function isWall(x, y) {
    return cubeMap[y][x] == 0;
}

function drawClickPos() {
    var p = clickPos;
    var buffer = [
        p[0], p[1], p[2],
        p[0] + 1.0, p[1], p[2],
        p[0], p[1] + 1.0, p[2]
    ];
    gl.bindBuffer(gl.ARRAY_BUFFER, clickPosVertexPosBuffer);
    gl.bufferSubData(gl.ARRAY_BUFFER, 0, new Float32Array(buffer));

    gl.bindBuffer(gl.ARRAY_BUFFER, clickPosVertexTextureCoordBuffer);
    gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, clickPosVertexTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);
    gl.bindBuffer(gl.ARRAY_BUFFER, clickPosVertexPosBuffer);
    gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, clickPosVertexPosBuffer.itemSize, gl.FLOAT, false, 0, 0);
    gl.activeTexture(gl.TEXTURE0);
    gl.bindTexture(gl.TEXTURE_2D, clickPosTexture);
    gl.uniform1i(shaderProgram.samplerUniform, 0);
    setMatrixUniforms();
    gl.drawArrays(gl.TRIANGLES, 0, clickPosVertexPosBuffer.numItems);
}

function getMouseVector(screenX, screenY) {
    var result = vec3.create([0, 0, 0]);
    var screenPosFar = vec3.create([screenX, gl.viewportHeight-screenY, 1]);
    var viewport = [0, 0, gl.viewportWidth, gl.viewportHeight];
    vec3.unproject(screenPosFar, mvMatrix, pMatrix, viewport, result);
    vec3.subtract(result, cameraPosition);
    vec3.normalize(result);
    return result;
}

function getElementOffset(el) {
    var _x = 0;
    var _y = 0;
    while( el && !isNaN(el.offsetLeft) && !isNaN(el.offsetTop)) {
        _x += el.offsetLeft - el.scrollLeft;
        _y += el.offsetTop - el.scrollTop;
        el = el.offsetParent;
    }
    return { top: _y, left: _x };
}
