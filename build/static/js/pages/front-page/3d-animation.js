"use strict";

document.addEventListener('DOMContentLoaded', function () {
  console.log('works');
  var scene = new THREE.Scene();
  var camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 0.1, 100);
  var renderer = new THREE.WebGLRenderer(); // const loader = new THREE.GLTFLoader();
  // console.log(loader)
  // console.log(THREE.GLTFLoader())

  var gltfLoader = new THREE.GLTFLoader();
  gltfLoader.load('http://localhost:8888/gomage/wp-content/themes/gomage/wraith.glb');
  scene.background = new THREE.Color(0xdddddd);
  renderer.setSize(window.innerWidth, window.innerHeight);
  document.body.appendChild(renderer.domElement);
  var material = new THREE.MeshPhongMaterial({
    color: 0x34d5eb,
    refractionRatio: 0.9
  });
  var cubeGeometry = new THREE.BoxGeometry(1, 1, 1);
  var cube = new THREE.Mesh(cubeGeometry, material);
  var geometry = new THREE.SphereGeometry(.5, 32, 32);
  var sphere = new THREE.Mesh(geometry, material);
  scene.add(sphere);
  var pointLight = new THREE.PointLight(0xffffff, 2);
  pointLight.position.x = 2;
  pointLight.position.y = 3;
  pointLight.position.z = 4;
  cube.position.y = -2;
  scene.add(cube); // scene.add(sphere);

  scene.add(pointLight);
  camera.position.z = 5; // camera.position.y = 7;

  var animate = function animate() {
    requestAnimationFrame(animate);
    renderer.render(scene, camera);
    cube.rotation.x += 0.005;
    cube.rotation.y += 0.001;
  };

  animate();
});