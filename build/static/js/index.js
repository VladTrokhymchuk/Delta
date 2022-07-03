// import {
//     CubeTextureLoader,
//     Scene,
//     WebGLRenderer,
//     WebGLRenderTarget,
//     SpotLight, 
//     PointLight,
//     AxesHelper,
//     AnimationMixer,
//     HalfFloatType,
//     HemisphereLight,
//     Clock,
//     ShaderMaterial,
//     Uniform,
//     Color,
//     Vector2,
//     CubeTexture,
//     TextureLoader,
// } from 'three';
// import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
// const maxScrollHeight = ($(window).height() * 11);
// const canvasPlaceSelector = document.querySelector('[data-hero-canvas-place]');
// const scene = new Scene()
// // scene.add(new AxesHelper(5))
// const light = new SpotLight()
// light.position.set(5, 5, 5)
// scene.add(light);
// const themeDir = document.body.dataset.themeDir;
// let perspectiveCamera;
// const mainRenderer = new WebGLRenderer({ 
//     antialias: true,
//     alphaToCoverage: true,
// })
// mainRenderer.setSize(window.innerWidth, window.innerHeight);
// canvasPlaceSelector?.appendChild(mainRenderer.domElement);
// const guiOptions = {
//   refractionIndex: 1,
//   color: "#FFFFFF",
//   dispersion: 0.1,
//   roughness: 0.9,
//   animation: true,
// };
// const renderTarget = new WebGLRenderTarget(
//   mainRenderer.domElement.offsetWidth,
//   mainRenderer.domElement.offsetHeight,
//   {
//     type: HalfFloatType
//   }
// );
// const material = new ShaderMaterial({
//   uniforms: {
//     resolution: new Uniform(
//       new Vector2(
//         mainRenderer.domElement.offsetWidth,
//         mainRenderer.domElement.offsetHeight
//       ).multiplyScalar(
//         window.devicePixelRatio
//       )
//     ),
//     backNormals: new Uniform(renderTarget.texture),
//     envMap: new Uniform(CubeTexture.DEFAULT_IMAGE),
//     refractionIndex: new Uniform(guiOptions.refractionIndex),
//     color: new Uniform(new Color(guiOptions.color)),
//     dispersion: new Uniform(guiOptions.dispersion),
//     roughness: new Uniform(guiOptions.roughness)
//   },
//   vertexShader: `
//   varying vec3 vWorldCameraDir;
//   varying vec3 vWorldNormal;
//   varying vec3 vViewNormal;
//   void main() {
//     vec4 worldPosition = modelMatrix * vec4( position, 1.0);
//     vWorldCameraDir = worldPosition.xyz - cameraPosition;
//     vWorldCameraDir = normalize(vec3(-vWorldCameraDir.x, vWorldCameraDir.yz));
//     vWorldNormal = (modelMatrix * vec4(normal, 0.0)).xyz;
//     vWorldNormal = normalize(vec3(-vWorldNormal.x, vWorldNormal.yz));
// 		vViewNormal = normalize( modelViewMatrix * vec4(normal, 0.0)).xyz;
//   	gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
//   }`,
//   fragmentShader: `
//   #define REF_WAVELENGTH 579.0
//   #define RED_WAVELENGTH 650.0
//   #define GREEN_WAVELENGTH 525.0
//   #define BLUE_WAVELENGTH 440.0
//   uniform vec2 resolution;
//   uniform sampler2D backNormals;
//   uniform samplerCube envMap;
//   uniform float refractionIndex;
//   uniform vec3 color;
//   uniform float dispersion;
//   uniform float roughness;
//   varying vec3 vWorldCameraDir;
//   varying vec3 vWorldNormal;
//   varying vec3 vViewNormal;
//   vec4 refractLight(float wavelength, vec3 backFaceNormal) {
//     float index = 1.0 / mix(refractionIndex, refractionIndex * REF_WAVELENGTH / wavelength, dispersion);
//     vec3 dir = vWorldCameraDir;
//     dir = refract(dir, vWorldNormal, index);
//     dir = refract(dir, backFaceNormal, index);
//     return textureCube(envMap, dir);
//   }
//   vec3 fresnelSchlick(float cosTheta, vec3 F0)
//   {
//     return F0 + (1.0 - F0) * pow(1.0 + cosTheta, 5.0);
//   }
//   void main() {
//     vec3 backFaceNormal = texture2D(backNormals, gl_FragCoord.xy / resolution).rgb;
//     float r = refractLight(RED_WAVELENGTH, backFaceNormal).r;
//     float g = refractLight(GREEN_WAVELENGTH, backFaceNormal).g;
//     float b = refractLight(BLUE_WAVELENGTH, backFaceNormal).b;
//     vec3 fresnel = fresnelSchlick(dot(vec3(0.0,0.0,-1.0), vViewNormal), vec3(0.04));
//     vec3 reflectedColor = textureCube(envMap, reflect(vWorldCameraDir, vWorldNormal)).rgb * saturate((1.0 - roughness) + fresnel);
//     gl_FragColor.rgb = vec3(r,g,b) * color + reflectedColor;
//   }`
// });
// setTimeout(() => {
//   canvasPlaceSelector.classList.add('rdy');
// }, 1000);
// scene.background = new TextureLoader()
//   .load(
//     `${themeDir}/build/static/images/general/resources/cubemap-new/px.png`,
//   );
// new CubeTextureLoader()
//   .setPath(`${themeDir}/build/static/images/general/resources/test-4/`)
//   .load(
//       ["px.png", "nx.png", "py.png", "ny.png", "pz.png", "nz.png"],
//       (texture) => {
//         material.uniforms.envMap.value = texture;
//       }
//   );
// window.addEventListener("resize", () => {
//   renderTarget.setSize(mainRenderer.domElement.offsetWidth, mainRenderer.domElement.offsetHeight);
//   material.uniforms.resolution.value.set(
//     window.devicePixelRatio * mainRenderer.domElement.offsetWidth,
//     window.devicePixelRatio * mainRenderer.domElement.offsetHeight
//   );
// });
// // Animation variables
// let mixer, mainAnim, action, actionsMap = [], transformAction;
// let scrollProgressPrecent, time, prevTime;
// let animationTotalTime,
//     animCurrentPresent = 0;
// // Figures variables 
// let trapezoidTopPart, trapezoidBottomPart, 
//     mainCube, pyramid, menu, menuCube, menuParts,
//     cubeMenuParts;
// const cubeMenuStep = document.querySelector('[data-hero-step="menu"]');
// const cubeMenuItems = cubeMenuStep.querySelectorAll('a');
// new GLTFLoader().load(
//     `${themeDir}/rdy-2.glb`,
//     function (gltf) {
//         const model = gltf.scene;
//         model.traverse(function(object) {
//             if (object.isMash) {
//                 object.castShadow = true;
//             }
//         })
//         console.log('model', model);
//         // mixer = new THREE.AnimationMixer(model);
//         // gltf.scene.scale.set(0.0001, 0.0001, 0.0001)
//         // delete gltf.scene.children[3];
//         // gltf.scene.translateY(1);
//         // gltf.scene.translateX(1);
//         scene.add(gltf.scene);
//         // console.log('mesh', mesh);
//         // scene.add(gltf.animations);
//         const sceneElements = gltf.scene.children[0].children;
//         console.log('sceneElements', sceneElements);
//         menu = sceneElements[1];
//         console.log('menu', menu);
//         // menu.children[1].visible = false;
//         pyramid = menu.children[2];
//         menuParts = menu.children[0];
//         menuCube = menu.children[1];
//         console.log('menuParts', menuParts);
//         cubeMenuParts = menuCube.children.reverse();
//         cubeMenuParts.shift();
//         // menu.children[1].visible = false;
//         // menuParts.children.forEach((part) => {
//         //   part.material = material;
//         // })
//         menuCube.children.forEach((part) => {
//             part.translateZ(3000)
//             // part.material.depthFunc = 2;
//         })
//         // console.log('pyramid', pyramid);
//         pyramid.visible = false;
//         console.log('sceneElements', sceneElements);
//         // pyramid.children[0].translateZ(100)
//         // pyramid.children[0].color.set( 0x03fce8 );
//         const trapezoidObj = sceneElements[2];
//         // console.log('sceneElements', sceneElements);
//         // trapezoidObj.children[0].children[0]
//         trapezoidObj.children[2].visible = false; // hide wrong static element
//         trapezoidObj.children[0].children[0].material = material;
//         trapezoidObj.children[0].children[0].children[0].material = material;
//         trapezoidObj.children[0].children[0].children[1].material = material;
//         trapezoidObj.children[0].children[1].children[0].material = material;
//         trapezoidObj.children[0].children[1].children[1].material = material;
//         trapezoidObj.children[0].children[1].material = material;
//         trapezoidObj.children[2].children[0].material = material;
//         trapezoidTopPart = trapezoidObj.children[0];
//         trapezoidBottomPart = trapezoidObj.children[1];
//         // trapezoidObj.children[3] = mesh;
//         mainCube = trapezoidObj.children[3];
//         mainCube.material.depthTest = true;
//         mainCube.material.roughness = 0;
//         mainCube.material.side = 3;
//         mainCube.material.metalness = 0.5;
//         console.log('trapezoidObj', trapezoidObj);
//         mainCube.material = material;
//         console.log('mainCube', mainCube);
//         trapezoidTopPart.scale.set(1.01, 1.01, 1.01);
//         trapezoidTopPart.visible = false;
//         trapezoidBottomPart.visible = false;
//         // trapezoidTopPart.material.side = 10;
//         trapezoidTopPart.children[0].material.depthFunc = 2;
//         trapezoidBottomPart.children[0].material.depthFunc = 2;
//         trapezoidTopPart.children[0].material = material;
//         trapezoidBottomPart.children[0].material = material;
//         console.log('trapezoidBottomPart', trapezoidBottomPart);
//         // Camera 
//         // =================================
//         perspectiveCamera = sceneElements[3];
//         // Animation 
//         // ================================
//         mixer = new AnimationMixer( model );
//         const excludeAnimList = [
//           // "Cube1_1.position",
//           // "Cube2_1.position",
//         ];
//         mainAnim = gltf.animations[ 0 ];
//         mainAnim.tracks = mainAnim.tracks.filter((track) => {
//             return !excludeAnimList.includes(track.name) ? track : false;
//         })
//         animationTotalTime = mainAnim.duration;
//         //mixer.setTime(9);
//         action = mixer.clipAction( mainAnim );
//         // introAction = mixer.clipAction( introAnimationActions );
//         // transformAction = mixer.clipAction( transformAnimationActions );
//         // console.log(action.getClip())
//         // prevAction = { ...action };
//         // introAction.play();
//         console.log('mainAnim', mainAnim);
//         action.play();
//         animate();
//         scrollProgressPrecent = (100 * window.scrollY) / maxScrollHeight;
//         scrollProgressPrecent =
//             scrollProgressPrecent > 65 && scrollProgressPrecent < 70
//                 ? 60
//                 : scrollProgressPrecent;
//         const time = ((animationTotalTime / 100) * scrollProgressPrecent).toFixed(9);
//         mixer?.setTime(time);
//     },
//     (xhr) => {
//         console.log((xhr.loaded / xhr.total) * 100 + '% loaded')
//     },
//     (error) => {
//         console.log(error)
//     }
// )
// window.addEventListener('resize', onWindowResize, false)
// function onWindowResize() {
//     // camera.aspect = window.innerWidth / window.innerHeight
//     // camera.updateProjectionMatrix()
//     perspectiveCamera.aspect = window.innerWidth / window.innerHeight
//     perspectiveCamera.updateProjectionMatrix()
//     mainRenderer.setSize(window.innerWidth, window.innerHeight)
//     render()
// }
// const animationProgressHandler = (precent = 0) => {
//     if (mainAnim !== undefined) {
//         const time = ((animationTotalTime / 100) * precent).toFixed(9);
//         mixer?.setTime(time);
//     }
// }
// let isMenuItemMouseInside = false;
// let hoveredMenuIndex = -1;
// cubeMenuItems.forEach((item, itemIndex) => {
//     item.addEventListener('mouseenter', () => {
//         isMenuItemMouseInside = true;
//         hoveredMenuIndex = itemIndex;
//     })
//     item.addEventListener('mouseleave', () => {
//         isMenuItemMouseInside = false;
//         hoveredMenuIndex = -1;
//     })
// });
// window.addEventListener('scroll', () => {
//     scrollProgressPrecent = (100 * window.scrollY) / maxScrollHeight;
//     // Play before pause in middle
//     if (scrollProgressPrecent < 45) {
//       animationProgressHandler(scrollProgressPrecent);
//     }
//     // Pause in middle
//     if (scrollProgressPrecent > 45 && scrollProgressPrecent < 55) {
//         !canvasPlaceSelector.matches('.hide') && canvasPlaceSelector.classList.add('hide');
//     } else {
//         canvasPlaceSelector.matches('.hide') && canvasPlaceSelector.classList.remove('hide');
//     }
//     // Play from paused point
//     if (scrollProgressPrecent > 55) {
//       animationProgressHandler(scrollProgressPrecent - 10);
//     }
//     // Skip animation part with cube parts movement
//     if (scrollProgressPrecent > 65) {
//       animationProgressHandler(60);
//     }
//     if (scrollProgressPrecent > 70) {
//       animationProgressHandler(scrollProgressPrecent + 17);
//     }
// })
// const pointLight = new PointLight(0x30bf9e, 3);
// const pointLight2 = new PointLight(0xb23cc9, 1);
// pointLight2.position.x = 0;
// pointLight2.position.y = 3;
// pointLight2.position.z = 0;
// scene.add(pointLight);
// scene.add(pointLight2);
// // const hemisphereLight = new HemisphereLight( 0xe8f8fa, 0x080820, 1 );
// // scene.add( hemisphereLight );
// const hemisphereLight = new HemisphereLight( 0x03fce8, 0x080820, 1 );
// scene.add( hemisphereLight );
// const hemisphereLight_2 = new HemisphereLight( 0x9134af, 0x080820, 1 );
// scene.add( hemisphereLight_2 );
// // const directionalLight = new THREE.DirectionalLight( 0x9134af, 0.5 );
// // scene.add( directionalLight );
// // const aLight = new THREE.AmbientLight( 0x404040 ); // soft white light
// // scene.add( aLight );
// let mouseX = 0, mouseY = 0;
// document.addEventListener('mousemove', (evt) => {
//   mouseX = evt.clientX;
//   mouseY = evt.clientY;
// })
// // const clock = new Clock();
// const changeObjectsByPrecent = (precent) => {
//     if (precent < 20) {
//         trapezoidTopPart.visible = false;
//         trapezoidBottomPart.visible = false;
//         mainCube.visible = true;
//     }
//     if (precent > 20) {
//         trapezoidTopPart.visible = true;
//         trapezoidBottomPart.visible = true;
//         mainCube.visible = false;
//     }
//     if (precent > 45) {
//         trapezoidTopPart.visible = false;
//         trapezoidBottomPart.visible = false;
//         mainCube.visible = false;
//     }
//     if (precent > 45) {
//         menu.children[1].visible = true;
//     } else {
//         menu.children[1].visible = false;
//     }
//     if (precent > 50) {
//         pyramid.visible = true;
//     } else {
//         pyramid.visible = false;
//     }
// }
// const translateValue = 40;
// const menuItemsTransitionHanlder = () => {
//     for (let i = 0; i <= cubeMenuItems.length - 1; i++) {
//         if (isMenuItemMouseInside && i == hoveredMenuIndex) {
//             if (cubeMenuParts[hoveredMenuIndex].position.z < translateValue) {
//                 cubeMenuParts[hoveredMenuIndex].position.z += 2;
//             }
//         }
//         if (cubeMenuParts[i].position.z > 0 && i !== hoveredMenuIndex) {
//             cubeMenuParts[i].position.z -= 2;
//         }
//     }
// }
// function animate() {
//     animCurrentPresent = (action.time * 100) / animationTotalTime;
//     changeObjectsByPrecent(animCurrentPresent);
//     menuItemsTransitionHanlder();
//     mainCube.rotation.y -= 0.005;
//     mainCube.rotation.x -= 0.005;
//     mainCube.rotation.z -= 0.005;
//     perspectiveCamera.aspect = window.innerWidth / window.innerHeight
//     perspectiveCamera.updateProjectionMatrix()
//     mainRenderer.render(scene, perspectiveCamera)
//     requestAnimationFrame(animate);
// }
// function render() {
//     mainRenderer.render(scene,  perspectiveCamera)
// }
"use strict";