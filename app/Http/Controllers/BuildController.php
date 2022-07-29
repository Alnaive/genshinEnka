<?php

namespace App\Http\Controllers;

use Redirect;
use Inertia\Inertia;
use App\Models\Build;
use App\Models\Character;
use App\Models\Artifact;
use App\Models\Pcs;
use App\Models\Constellation;
use App\Models\Talent;
use App\Models\Weapon;

use Illuminate\Support\Facades\Request;
// use Illuminate\Http\Request;

use App\Http\Requests\BuildRequest;
use Stevebauman\Location\Facades\Location;
use Auth;
use URL;
class BuildController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Build::with('user','character','character.talent','character.constellation', 'party', 'set4','set2','flower','plume','sand','goblet','circlet','weapon')->where('status', 'publish');
        if(request('search')){
            $query->whereHas('user', function($q){
                $q->where('name', 'LIKE', '%'.request('search').'%');
            })
            ->orWhereHas('character', function($q){
                $q->where('name',  'LIKE', '%'.request('search').'%');
            });
        }
        return Inertia::render('Builds/index', [
            'filters' => Request::all('search'),
            'characters' => Character::all(),
            'artifactPcs' => Pcs::all(),
            'weapons' => Weapon::all(),
            'builds' => $query->latest()->paginate(10)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function storeBuild(BuildRequest $request)
    { 
        $build = Build::where(['uid' =>  $request->uid, 'character_id' => $request->character_id, 'four_pcs_art' => $request->four_pcs_art,])
                ->whereJsonContains('two_pcs_art', $request->two_pcs_art)
                ->first();
        if($build) //will be empty if no result
        $build->update([
            'nickname' => $request->nickname,
            'ascendsion' => $request->ascendsion, 
            'level' => $request->level, 
            'conste' => $request->conste,
            'weapon_id' => $request->weapon_id,
            'refinement' => $request->refinement,
            'one_pcs_art' => $request->one_pcs_art,
            'equipList' => $request->equipList,
            'sands' => $request->sands,
            'goblet' => $request->goblet,
            'circlet' => $request->circlet,
            'talent' => $request->talent,
            'talentExtraLv' => $request->talentExtraLv, 
            'hp' => $request->hp,
            'attack' => $request->attack, 
            'defense' => $request->defense,
            'elementalMastery' => $request->elementalMastery, 
            'criticalRate' => $request->criticalRate,
            'criticalDamage' => $request->criticalDamage,
            'healingBonus' => $request->healingBonus,
            'energyRecharge' => $request->energyRecharge,
            'pyroDamageBonus' => $request->pyroDamageBonus,
            'hydroDamageBonus' => $request->hydroDamageBonus,
            'anemoDamageBonus' => $request->anemoDamageBonus,
            'electroDamageBonus' => $request->electroDamageBonus,
            'dendroDamageBonus' => $request->dendroDamageBonus,
            'cryoDamageBonus' => $request->cryoDamageBonus, 
            'geoDamageBonus' => $request->geoDamageBonus, 
            'physicalDamageBonus' => $request->physicalDamageBonus,
            'status' => $request->status,
        ]);
        else
        $build = Build::create([
            'uid' => $request->uid, 
            'nickname' => $request->nickname,
            'character_id' => $request->character_id, 
            'ascendsion' => $request->ascendsion, 
            'level' => $request->level, 
            'conste' => $request->conste,
            'weapon_id' => $request->weapon_id,
            'refinement' => $request->refinement,
            'four_pcs_art' => $request->four_pcs_art, 
            'two_pcs_art' => $request->two_pcs_art,
            // 'two_pcs_art' => (!empty($request->two_pcs_art)) ? $request->two_pcs_art : null,
            'one_pcs_art' => $request->one_pcs_art,
            'equipList' => $request->equipList,
            'sands' => $request->sands,
            'goblet' => $request->goblet,
            'circlet' => $request->circlet,
            'talent' => $request->talent,
            'talentExtraLv' => $request->talentExtraLv, 
            'hp' => $request->hp,
            'attack' => $request->attack, 
            'defense' => $request->defense,
            'elementalMastery' => $request->elementalMastery, 
            'criticalRate' => $request->criticalRate,
            'criticalDamage' => $request->criticalDamage,
            'healingBonus' => $request->healingBonus,
            'energyRecharge' => $request->energyRecharge,
            'pyroDamageBonus' => $request->pyroDamageBonus,
            'hydroDamageBonus' => $request->hydroDamageBonus,
            'anemoDamageBonus' => $request->anemoDamageBonus,
            'electroDamageBonus' => $request->electroDamageBonus,
            'dendroDamageBonus' => $request->dendroDamageBonus,
            'cryoDamageBonus' => $request->cryoDamageBonus, 
            'geoDamageBonus' => $request->geoDamageBonus, 
            'physicalDamageBonus' => $request->physicalDamageBonus,
            'status' => $request->status,
        ]);
        // $build = Build::updateOrCreate(
        //     ['uid' =>  $request->uid, 
        //     'character_id' => $request->character_id,
        //     'four_pcs_art' => $request->four_pcs_art, 
        //     'two_pcs_art' => $request->two_pcs_art,
        //     ],
        //     [
        //     'uid' => $request->uid, 
        //     'nickname' => $request->nickname,
        //     'character_id' => $request->character_id, 
        //     'ascendsion' => $request->ascendsion, 
        //     'level' => $request->level, 
        //     'conste' => $request->conste,
        //     'weapon_id' => $request->weapon_id,
        //     'refinement' => $request->refinement,
        //     'four_pcs_art' => $request->four_pcs_art, 
        //     'two_pcs_art' => (!empty($request->two_pcs_art)) ? [$request->two_pcs_art, true] : null,
        //     'one_pcs_art' => $request->one_pcs_art,
        //     'equipList' => $request->equipList,
        //     'sands' => $request->sands,
        //     'goblet' => $request->goblet,
        //     'circlet' => $request->circlet,
        //     'talent' => $request->talent,
        //     'talentExtraLv' => $request->talentExtraLv, 
        //     'hp' => $request->hp,
        //     'attack' => $request->attack, 
        //     'defense' => $request->defense,
        //     'elementalMastery' => $request->elementalMastery, 
        //     'criticalRate' => $request->criticalRate,
        //     'criticalDamage' => $request->criticalDamage,
        //     'healingBonus' => $request->healingBonus,
        //     'energyRecharge' => $request->energyRecharge,
        //     'pyroDamageBonus' => $request->pyroDamageBonus,
        //     'hydroDamageBonus' => $request->hydroDamageBonus,
        //     'anemoDamageBonus' => $request->anemoDamageBonus,
        //     'electroDamageBonus' => $request->electroDamageBonus,
        //     'dendroDamageBonus' => $request->dendroDamageBonus,
        //     'cryoDamageBonus' => $request->cryoDamageBonus, 
        //     'geoDamageBonus' => $request->geoDamageBonus, 
        //     'physicalDamageBonus' => $request->physicalDamageBonus,
        //     'status' => $request->status,
        //      ]
        // );
            return Redirect::route('showBuild', $build);
        
            // $data = $request->all();
            // $build = Build::create($data);
            // return Redirect::route('showBuild', $build);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Build  $build
     * @return \Illuminate\Http\Response
     */

    public function show( Build $build, $id)
    {}

    public function seeBuild($slug)
    {
        $character = Character::where('slug',$slug)->first();
        $build = Build::query()->with('character','weapon','set4','set2','likes')->where('status','publish');
        if(request('search')){
            $build->where('name', 'LIKE', '%'.request('search').'%')
            ->orWhere('char_lv', 'like', '%'.request('search').'%')
                ->orWhere('c_rate', 'like', '%'.request('search').'%')
                ->orWhere('c_damage', 'like', '%'.request('search').'%')
                ->orWhere('em', 'like', '%'.request('search').'%')
                ->orWhere('defense', 'like', '%'.request('search').'%')
                ->orWhere('name', 'like', '%'.request('search').'%');
        }
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        return Inertia::render('Builds/build', [
            'filters' => Request::all('search'),
            'character' => $character,
            'build' => $build->withCount('likes')->orderBy('likes_count', 'desc')->paginate(10)->withQueryString(),
            'search_url' => URL::route('seeBuild', $slug),
        ]);
    }
    public function showBuild( $id)
    {
    
        $build = Build::with('character','weapon')->find($id);

        return Inertia::render('Builds/showBuild', [
            'build' => $build,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Build  $build
     * @return \Illuminate\Http\Response
     */
    public function editBuild($id)
    {
        $build = Build::with('character','character.constellation','character.talent')->find($id);
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        if(auth::check()){
            if(auth()->user()->id == $build->user_id ||  auth()->user()->role == 'admin'){
                $weapon = Weapon::orderBy('name', 'asc')->get();
                    foreach($weapon as $data){
                        if($data->type == $build->character->type){
                            $weapons[] = $data;
                        }
                    }
                    $artifacts =  Artifact::with('pcs')->orderBy('paramRarity','desc')->orderBy('name','asc')->get();
                    $artPcs =  Pcs::with('artifact')->orderBy('rarity','desc')->orderBy('name', 'asc')->get();
                
                    $flowers = Pcs::where('relictype', 'Flower of Life')->orderBy('rarity','desc')->orderBy('name','asc')->get();
                    $plumes = Pcs::where('relictype', 'Plume of Death')->orderBy('rarity','desc')->orderBy('name','asc')->get();
                    $sands = Pcs::where('relictype', 'Sands of Eon')->orderBy('rarity','desc')->orderBy('name','asc')->get();
                    $goblets = Pcs::where('relictype', 'Goblet of Eonothem')->orderBy('rarity','desc')->orderBy('name','asc')->get();
                    $circlets = Pcs::where('relictype', 'Circlet of Logos')->orderBy('rarity','desc')->orderBy('name','asc')->get();
                    return Inertia::render('Builds/editBuild', [
                        'build' => $build,
                        'characters' => Character::orderBy('name', 'asc')->get(),
                        'artifacts' => $artifacts,
                        'artifactPcs' => $artPcs,
                        'flowers' => $flowers,
                        'plumes' => $plumes,
                        'sands' => $sands,
                        'goblets' => $goblets,
                        'circlets' => $circlets,
                        'weapons' => $weapons,
                    ]); 
            } else {
                abort(403);
            }
        } elseif($build->ip == $ipAddress){
            $weapon = Weapon::orderBy('name', 'asc')->get();
                    foreach($weapon as $data){
                        if($data->type == $build->character->type){
                            $weapons[] = $data;
                        }
                    }
                    $artifacts =  Artifact::with('pcs')->orderBy('paramRarity','desc')->orderBy('name','asc')->get();
        $artPcs =  Pcs::with('artifact')->orderBy('rarity','desc')->orderBy('name', 'asc')->get();
                
                    $flowers = Pcs::where('relictype', 'Flower of Life')->orderBy('rarity','desc')->orderBy('name','asc')->get();
                    $plumes = Pcs::where('relictype', 'Plume of Death')->orderBy('rarity','desc')->orderBy('name','asc')->get();
                    $sands = Pcs::where('relictype', 'Sands of Eon')->orderBy('rarity','desc')->orderBy('name','asc')->get();
                    $goblets = Pcs::where('relictype', 'Goblet of Eonothem')->orderBy('rarity','desc')->orderBy('name','asc')->get();
                    $circlets = Pcs::where('relictype', 'Circlet of Logos')->orderBy('rarity','desc')->orderBy('name','asc')->get();
                    return Inertia::render('Builds/editBuild', [
                        'build' => $build,
                        'characters' => Character::orderBy('name', 'asc')->get(),
                        'artifacts' => $artifacts,
                        'artifactPcs' => $artPcs,
                        'flowers' => $flowers,
                        'plumes' => $plumes,
                        'sands' => $sands,
                        'goblets' => $goblets,
                        'circlets' => $circlets,
                        'weapons' => $weapons,
                    ]); 
        } else {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Build  $build
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Build $build)
    {
        //
    }

    public function updateBuild(BuildRequest $request, $id){
        $build = Build::find($id);
        $build->update([
            'ascension' => $request->ascension,
            'char_lv' => $request->char_lv,
            'conste' => $request->conste,
            'weapon_id' => $request->weapon_id,
            'refinement' => $request->refinement,
            'four_pcs_art' => $request->four_pcs_art,
            'two_pcs_art' => $request->two_pcs_art,
            'flower_id' => $request->flower_id,
            'plume_id' => $request->plume_id,
            'sand_id' => $request->sand_id,
            'goblet_id' => $request->goblet_id,
            'circlet_id' => $request->circlet_id,
            'main_sand' => $request->main_sand,
            'main_goblet' => $request->main_goblet,
            'main_circlet' => $request->main_circlet,
            'talent1' => $request->talent1,
            'talent2' => $request->talent2,
            'talent3' => $request->talent3,
            'party_id' => $request->party_id,
            'hp' => $request->hp,
            'atk' => $request->atk,
            'defense' => $request->defense,
            'em' => $request->em,
            'c_rate' => $request->c_rate,
            'c_damage' => $request->c_damage,
            'healing_bonus' => $request->healing_bonus,
            'er' => $request->er,
            'elemental_dmg' => $request->elemental_dmg,
            'physical_dmg' => $request->physical_dmg,
            'source' => $request->source,
            'name' => $request->name,
            'status' => $request->status,
            
        ]);
        return Redirect::route('showBuild', $build);

    }

    public function updateBuildStatus(Request $request, $id){
        $build = Build::find($id);
        $build->update([
            'status' => 'publish',
        ]);
        return Redirect()->back();
    }

    public function updateBuildAccount(Request $request, $id){
        $build = Build::find($id);
        $build->update([
            'user_id' => Auth::id(),
            'ip' => \Request::ip(),
            'country' => Location::get('ip')->countryName,
            'discord_id' => auth()->user()->discord_id,
            'name' => auth()->user()->name,
        ]);
        return Redirect()->back();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Build  $build
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $build = Build::find($id);
        $build->delete();
        return Redirect::route('Builds.index');
    }

    public function destroyBuild($id)
    {
        $build = Build::find($id);
        $build->delete();
        return Redirect::route('welcome');
    }
   
    public function destroyDraft($id)
    {
        $build = Build::find($id);
        $build->delete();
        return Redirect::route('Dashboard');
    }
}