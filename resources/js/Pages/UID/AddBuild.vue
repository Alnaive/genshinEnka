<template>
     <Guest>
        <br>
        <div class="flex gap-2">
            <Link class="btn btn-ghost" :href="route('uid', { id: uid} )">Back</Link>
        </div>
        <div class="alert alert-info justify-center" v-if="sessionData.propMap[4001]['val'] < 79"><span>Level Up Your Character to Store in This Site!</span></div>

        <br>
        <div class="form-control w-full max-w-xs">
            <label class="label">
                <span class=" text-black dark:text-white">Change Avatar</span>
            </label>
            <input type="file" @change="onFileChange" ref="Avatar"/>
        </div>
        <div class="container invisible hover:visible">
            <Moveable
                className="moveable"
                v-bind:target="['.target']"
                v-bind:draggable="true"
                v-bind:scalable="true"
                v-bind:rotatable="false"
                @drag="onDrag"
                @scale="onScale"
                @rotate="onRotate"
            />
        </div>
        <div class="space-y-2 lg:flex lg:space-x-2">
            <div  class="flex flex-row card shadow-lg overflow-x-auto" >
                <ul id="landscapeData" class="flex flex-row space-x-4 rounded-xl" :style="{ backgroundImage: 'url(' + bgElement() + ')',  backgroundRepeat: 'no-repeat'}">
                    <li>
                        <div  class="w-[414px] h-[736px] card shadow-lg  shadow-cyan-500/50 shadow-inner" >
                        <figure id="preview" class="target " ref="target">
                            <img v-if="!url" :src="showAvatar() + charData.avatar" >
                            <img v-else :src="url" />
                        </figure> 
                        <div :class="!url ? 'text-white font-bold absolute ml-4 top-0 mt-1':'mix-blend-color-dodge drop-shadow-2xl font-bold absolute ml-4 top-0 mt-1'">
                        <h1 class="font-md">{{charData.name}}</h1> 
                        <span v-show="!showNickname">{{playerInfo.nickname}} </span>   
                        <pre :class="!url ? 'text-warning' : 'text-none'">Level {{sessionData.propMap[4001]['val']}}/{{(sessionData.propMap[1002]['val'] * 10) + (sessionData.propMap[1002]['val']>0?10:0) + 20}}</pre> 
                        </div>
                        <div class="absolute top-0 right-0 mt-1 mr-[12px]"><h1 class="font-bold" v-show="!showUid"> {{uid}}</h1></div>
                        <section v-show="!showEquip">
                        <Equip :sessionData="sessionData" />
                        </section>
                        
                        <Conste :sessionData="sessionData" :charData="charData" />

                        <Talent :sessionData="sessionData" :charData="charData" :showEquip="showEquip" /> 
                        <div class="card absolute inset-x-0 bottom-0 mt-5">
                            <div v-if="!url" >
                                <section class="px-2 py-2 text-white" >
                                    <Stats :sessionData="sessionData" :charData="charData" />
                                </section>
                            </div>
                            <div v-else :style="{ backgroundImage: 'url(' + bgElement() + ')'}">
                             <section class="px-2 pt-2 text-white" >
                                    <Stats :sessionData="sessionData" :charData="charData" />
                                </section>
                            </div>
                        </div>
                </div>
                    </li>
                    <li>
                <div class="space-y-1 mt-3 mr-2 text-white">
                    <Weapon :sessionData="sessionData" :weapons="weapons"/>
                    <Flower :sessionData="sessionData"/>
                    <Plume :sessionData="sessionData"/>
                    <Sands :sessionData="sessionData"/>
                    <Goblet :sessionData="sessionData"/>
                    <Circlet :sessionData="sessionData"/>
                    <div  class="flex space-x-3 justify-center" >
                        <div class="flex-none ...">
                        <div class="flex flex-col" v-for="(key,item) in countedOfArtifact" :key="key">
                                <div  v-if="key == 2">
                                    <div class="font-bold whitespace-nowrap text-md">{{item}}</div>
                                </div>
                                <div v-else-if="key == 3">
                                    <div class="font-bold whitespace-nowrap text-md mt-[12px]">{{item}}</div>
                                </div>
                                <div v-else-if="key == 4">
                                    <div class="font-bold whitespace-nowrap text-md mt-[12px]">{{item}}</div>
                                </div>
                        </div>
                        </div>
                        <div class="flex-none ...">
                            <div class="flex flex-col "  v-for="(key, item) in countedOfArtifact" :key="key">
                                <div  v-if="key == 2">
                                    <div class="badge">{{key}}</div>
                                </div>
                                <div v-else-if="key == 3">
                                    <div class="badge mt-[15px]">{{key}}</div>
                                </div>
                                <div v-else-if="key == 4">
                                    <div class="badge mt-[15px]">{{key}}</div>
                                </div>
                            </div>
                                
                        </div>
                    </div>
                </div>
                    
                    </li>
                </ul>
            </div>
        
        </div>
        <div class="flex space-x-2 mt-4 ">
            <button v-if="sessionData.propMap[4001]['val'] > 79" class="btn bg-red-500 hover:bg-red-400 text-white" @click="submit()"><VueFeather type="upload" size="24" class="mr-1" ></VueFeather>Store</button>
            
            <!-- The button to open modal -->
            <label for="my-modal-4" class="btn bg-green-500 text-white hover:bg-green-400"><VueFeather type="image" size="24" class="mr-1" ></VueFeather> Export</label>

            <!-- Put this part before </body> tag -->
            <input type="checkbox" id="my-modal-4" class="modal-toggle" />
            <label for="my-modal-4" class="modal cursor-pointer">
            <label class="modal-box relative bg-accent-content dark:bg-base-100" for="">
                    <label for="my-modal-4" class="btn btn-sm btn-circle absolute right-2 top-2 bg-transparent">✕</label>

                <div class="flex flex-col space-x-2 md:flex-row items-center justify-center">
                    <button  v-on:click="showEquip = !showEquip" class="btn btn-primary space-x-2"><VueFeather :type=" !showEquip ? 'eye-off' : 'eye' " size="24" class="mr-1"></VueFeather>Left Equip</button>
                    <button  v-on:click="showNickname = !showNickname" class="btn btn-primary space-x-2"><VueFeather :type=" !showNickname ? 'eye-off' : 'eye' " size="24" class="mr-1"></VueFeather>Nickname</button>
                    <button  v-on:click="showUid = !showUid" class="btn btn-primary space-x-2"><VueFeather :type=" !showUid ? 'eye-off' : 'eye' " size="24" class="mr-1"></VueFeather>UID</button>
                </div>
                <br>
                <div class="flex items-center justify-center">
                    <div class="flex space-x-4">
                        <!-- <button @click="portraitImage()" class="btn btn-primary space-x-2"><VueFeather type="smartphone" size="24"></VueFeather></button> -->
                        <button @click="landscapeImage()" class="btn btn-primary space-x-2">Export</button>
                    </div>
                </div>
                 <div class="flex flex-col lg:flex-row mt-2 overflow-auto">
                        <div class="bg-transparent " id="canvas-landscape"></div>
                    </div>
            </label>
            </label>
            
        </div>
    </Guest>
</template>

<script>
import { Head, Link, useForm, usePage  } from '@inertiajs/inertia-vue3';
import Guest from '@/Layouts/Guest.vue';
import Equip from './Component/Equip.vue';
import Talent from './Component/Talent.vue';
import Stats from './Component/Stats.vue';
import Conste from './Component/Conste.vue';
import Weapon from './Component/Weapon.vue';
import Flower from './Component/Flower.vue';
import Plume from './Component/Plume.vue';
import Sands from './Component/Sands.vue';
import Goblet from './Component/Goblet.vue';
import Circlet from './Component/Circlet.vue';
import { EncryptStorage } from 'encrypt-storage';
import domtoimage from 'dom-to-image';
import { saveAs } from 'file-saver';
import VueFeather from 'vue-feather';
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/src/sweetalert2.scss'
import Moveable from "vue3-moveable";

export default {
    components: {
      Head,
      Link,
      Guest, Moveable,
        Equip, Talent, Stats, Conste, VueFeather,Weapon, Flower, Goblet,
       Plume, Sands, Circlet,
    },
    props: {
        uid: String,
        itemName: Object,
        weapons: Object,
        sessionData: {
                type: Object,
                default(rawProps) {
                    const encryptStorage = new EncryptStorage(process.env.MIX_SECRET_KEY, {
                        storageType: 'sessionStorage',
                    });
                    return JSON.parse(JSON.stringify(encryptStorage.getItem('sessionData'))) || []
                }
            },
         charData: {
                type: Object,
                default(rawProps) {
                    const encryptStorage = new EncryptStorage(process.env.MIX_SECRET_KEY, {
                        storageType: 'sessionStorage',
                    });
                    return JSON.parse(JSON.stringify(encryptStorage.getItem('charData'))) || []
                }
            },
        playerInfo: {
                type: Object,
                default(rawProps) {
                     const encryptStorage = new EncryptStorage(process.env.MIX_SECRET_KEY, {
                        storageType: 'sessionStorage',
                    });
                    return JSON.parse(JSON.stringify(encryptStorage.getItem('playerInfo'))) || []
                }
            },
    },
    
    setup(props){
        const form = useForm({
            uid: props.uid,
            nickname: props.playerInfo.nickname,
            character_id: null,
            ascendsion: props.sessionData.propMap[1002]['val'],
            level: props.sessionData.propMap[4001]['val'],
            conste: null,
            weapon_id: props.sessionData.equipList[5].itemId,
            refinement: null,
            four_pcs_art: null,
            two_pcs_art: [],
            one_pcs_art: null,
            equipList: props.sessionData.equipList,
            sands: props.sessionData.equipList[2].flat.reliquaryMainstat.mainPropId,
            goblet: props.sessionData.equipList[3].flat.reliquaryMainstat.mainPropId,
            circlet: props.sessionData.equipList[4].flat.reliquaryMainstat.mainPropId,
            normalAttack:null,
            elementalSkill: null,
            elementalBurst: null,
            talent: props.sessionData.skillLevelMap,
            talentExtraLv: null,
            hp: props.sessionData.fightPropMap[2000].toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 0}),
            attack: props.sessionData.fightPropMap[2001].toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 0}),
            defense: props.sessionData.fightPropMap[2002].toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 0}),
            elementalMastery: props.sessionData.fightPropMap[28].toLocaleString(undefined, {minimumFractionDigits: 0, maximumFractionDigits: 0}),
            criticalRate: parseFloat(props.sessionData.fightPropMap[20] * 100).toFixed(1),
            criticalDamage: parseFloat(props.sessionData.fightPropMap[22] * 100).toFixed(1),
            healingBonus:parseFloat(props.sessionData.fightPropMap[26] * 100).toFixed(1),
            energyRecharge: parseFloat(props.sessionData.fightPropMap[23] * 100).toFixed(1),
            pyroDamageBonus: parseFloat(props.sessionData.fightPropMap[40] * 100).toFixed(1),
            hydroDamageBonus: parseFloat(props.sessionData.fightPropMap[42] * 100).toFixed(1),
            anemoDamageBonus: parseFloat(props.sessionData.fightPropMap[44] * 100).toFixed(1),
            electroDamageBonus: parseFloat(props.sessionData.fightPropMap[41] * 100).toFixed(1),
            dendroDamageBonus: parseFloat(props.sessionData.fightPropMap[43] * 100).toFixed(1),
            cryoDamageBonus: parseFloat(props.sessionData.fightPropMap[46] * 100).toFixed(1),
            geoDamageBonus: parseFloat(props.sessionData.fightPropMap[45] * 100).toFixed(1),
            physicalDamageBonus: parseFloat(props.sessionData.fightPropMap[30] * 100).toFixed(1),
            serverId: null,
        });
            return {form }
        },
        
    data(){
        return{
           arrayOfArtifact: [],
           countedOfArtifact: {},
           arrayOfTalent: [],
           url:null,
           showEquip: false,
           showUid: false,
           showNickname: false,
           twopcs: [],
           onepcs: null,
           fourpcs: null,
        }
    },
    methods:{
    onDrag({ transform }) {
        this.$refs.target.style.transform = transform;
        },
        onScale({ drag }) {
        this.$refs.target.style.transform = drag.transform;
        },
        onRotate({ drag }) {
        this.$refs.target.style.transform = drag.transform;
        },
       submit() {
            Swal.fire({
                title: "Are you sure ?",
                text: "Your UID will be store!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ff0000",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false 
            }).then((result) => {
            if (result.value) {
                this.form.post(route("storeBuild"));
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                Swal.fire(
                'Cancel',
                'Data is not saved! ',
                'error'
                )
            }
            })
        },
        landscapeImage(){
                var node = document.getElementById("landscapeData");
                var node1 = document.getElementById("canvas-landscape");

                domtoimage
                .toPng(node)
                .then(function (dataUrl) {
                    var img = new Image();
                    img.src = dataUrl;
                    node1.appendChild(img);
                })
                .catch(function (error) {
                    console.error("oops, something went wrong!", error);
                });
            },
        showAvatar() {
                return "/storage/images/icon/avatar/";
            }, 
         onFileChange(e) {
            const file = e.target.files[0];
            this.url = URL.createObjectURL(file);
        },
        bgElement(){
            const ele = this.charData.element;
                if(ele == "Ice"){
                    return "../image/element/bgCryo.jpg"
                } else if(ele == "Rock"){
                    return "../image/element/bgGeo.jpg"
                } else if(ele == "Dendro"){
                    return "../image/element/bgDendro.jpg"
                } else if(ele == "Wind"){
                    return "../image/element/bgAnemo.jpg"
                } else if(ele == "Electric"){
                    return "../image/element/bgElectro.jpg"
                } else if(ele == "Water"){
                    return "../image/element/bgHydro.jpg"
                } else if(ele == "Fire"){
                    return "../image/element/bgPyro.jpg"
                } else {
                    return "../image/element/bgDendro.jpg"
                }
            },
            defineArtifact(){
                // Object.keys(this.sessionData.equipList).map((key) => {
                //     Object.keys(this.itemName).map((item) => {
                //         if(item == this.sessionData.equipList[key].flat.setNameTextMapHash){
                //             return this.arrayOfArtifact.push(this.itemName[item].EN)
                //         }
                //     })
                // })
                Object.keys(this.sessionData.equipList).map((key) => {
                    Object.keys(this.itemName.en).map((item) => {
                        if(item == this.sessionData.equipList[key].flat.setNameTextMapHash){
                            return this.arrayOfArtifact.push(this.itemName.en[item])
                        }
                    })
                })
            },
            countArrayArt(){
                this.arrayOfArtifact.forEach(element => {
                    this.countedOfArtifact[element] = (this.countedOfArtifact[element] || 0) + 1;
                });
            },
            artifactSet(){
                Object.keys(this.countedOfArtifact).forEach((key) => {
                    if(this.countedOfArtifact[key] == 1){
                        null
                    } else if(this.countedOfArtifact[key] < 4){
                    this.form.two_pcs_art.push(key);
                    } else if(this.countedOfArtifact[key] == 4){
                    this.form.four_pcs_art = key;
                    }
                });
            },
            defineRefinement(){
                Object.values(this.sessionData.equipList[5].weapon.affixMap).map((item) => {
                    this.form.refinement = item;
                });
            },
            arrayTalent(){
                Object.keys(this.charData.SkillOrder).map((skillKey) => {
                    Object.keys(this.sessionData.skillLevelMap).map((itemKey) => {
                        if(this.charData.SkillOrder[skillKey] == itemKey){
                            this.arrayOfTalent.push(this.sessionData.skillLevelMap[itemKey])
                        }
                    })
                })
                
            },
            defineCharacterId(){
                const charId = this.charData.id;
                const depotId = this.sessionData.skillDepotId;
                if(depotId == '504'){
                    return this.form.character_id = 10000005
                }
                if(depotId == '504'){
                    return this.form.character_id = 10000005;
                } else if(depotId == '506') {
                    return this.form.character_id = "10000005-506";
                } else if(depotId == '507') {
                    return this.form.character_id = "10000005-507";
                } else if(depotId == '508') {
                    return this.form.character_id = "10000007-508";
                } else if(depotId == '704') {
                    return this.form.character_id = "10000007";
                } else if(depotId == '706') {
                    return this.form.character_id = "10000007-706";
                } else if(depotId == '707') {
                    return this.form.character_id = "10000007-707";
                } else if(depotId == '708') {
                    return this.form.character_id = "10000007-708";
                }  else {
                    return this.form.character_id = charId;
                }
            },
            defineTalent(){
                const talent = this.arrayOfTalent;
                if(this.charData.skillDepotId == '201' || '504' || '506' || '507' || '508' || '704' || '706' || '707' || '708'){
                    this.form.normalAttack = talent[2];
                    this.form.elementalSkill = talent[0];
                    this.form.elementalBurst = talent[1];
                } else {
                    this.form.normalAttack = talent[0];
                    this.form.elementalSkill = talent[1];
                    this.form.elementalBurst = talent[2];
                }
                
            },
            defineSkill(){
                if(this.sessionData.proudSkillExtraLevelMap){
                    this.form.talentExtraLv = this.sessionData.proudSkillExtraLevelMap;
                } else {
                    null
                }
            },
            defineConstellation(){
                const constellation = this.sessionData.talentIdList;
                if(!constellation){
                    this.form.conste = 0;
                } else if(constellation.length == 1){
                    this.form.conste = 1;
                } else if(constellation.length == 2){
                    this.form.conste = 2;
                } else if(constellation.length == 3){
                    this.form.conste = 3;
                } else if(constellation.length == 4){
                    this.form.conste = 4;
                } else if(constellation.length == 5){
                    this.form.conste = 5;
                } else if(constellation.length == 6){
                    this.form.conste = 6;
                }
            },
            serverUID(){
                let x = this.uid.charAt(0);
                if(x = 8){
                     this.form.serverId = 'Asia'
                } else if(x = 1 || 2 || 5){
                     this.form.serverId = 'China'
                } else if(x = 6){
                     this.form.serverId = 'America'
                } else if(x = 7){
                     this.form.serverId = 'Europe'
                } else if(x = 9){
                     this.form.serverId = 'TW, HK, MO'
                }
            }
    },
    async mounted(){
        this.defineCharacterId();
        await this.defineArtifact();
        await this.countArrayArt();
        await this.artifactSet();
        await this.defineRefinement();
        await this.defineSkill();
        await this.defineConstellation();
        await this.serverUID();
        this.arrayTalent();
        this.defineTalent();
    }
}


</script>
