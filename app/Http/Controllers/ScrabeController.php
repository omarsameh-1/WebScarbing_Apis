<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\ScrabedWebsite;
use App\Models\Article;
use App\Models\Opreation;
use Goutte\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Http\Factory\Guzzle\ResponseFactory;
use Http\Factory\Guzzle\ServerRequestFactory;
use Http\Factory\Guzzle\StreamFactory;
use Http\Factory\Guzzle\Storage;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;


class ScrabeController extends Controller
{
    
    public function index()
    {
        return ScrabedWebsite::all();
    }

    
    public function ScrabeWebsiteByLink(Request $request)
    {
        $userid = auth('sanctum')->user()->id;
        
        $fields = $request->validate([
            'link' => 'required|string',
            'name' => 'required|string'
        ]);
        
       
            if(!($fields['name']))
            {
                $website = ScrabedWebsite::create([
                    'link' => $fields['link'],
                    'name'=> $fields['name'],
                    'createddate'=> date("Y-m-d"),
                    'userid' => $userid
                ]);
            }
    
        if ($fields['name']=='mklat')
        {
            $client = new Client();
            $crawler1 = $client->request('GET',$fields['link']);
            //trying to get over the foreinge key ishuse
            $ids3=ScrabedWebsite::where('name','mklat')->value('id');
            $id=Opreation::latest('updated_at')->value('id');
            if($id==null){
                $id=0;
            }
            Opreation::create([
                'website_id' => $ids3,
                'user_id' => $userid
            ]);
            $data=[];
            $div = $crawler1->filter('.mag-box-container ul li ')->each(function($liElement)use(&$data,&$id,&$ids3)
            {
                if ($post_details=$liElement->filter('.post-details ')->count()>0)
                {
                $post_details=$liElement->filter('.post-details ')->children();
                $title= $post_details->eq(1)->text();
                $Disc= $post_details->eq(2)->text();
                $link= $post_details->filter('.post-title a')->first()->attr('href');
                $innnerData['title'] = $title;
                $innnerData['Discribtion'] = $Disc;
                $innnerData['link'] = $link;
                $innnerData['created_at']= date("Y-m-d");
                $data[]=$innnerData;
                
                Article::create([
                    'art_link' => $link,
                    'title' => $title,
                    'disc' =>  $Disc,
                    'website_id' =>$ids3,
                    'opr_id' => $id + 1
                ]);
                }
            });
        }
        elseif($fields['name']=='arabmediasociety')
        {
            $client2 = new Client();
            $crawler2= $client2->request('GET',$fields['link']);

            //trying to get over forein key
            $ids=ScrabedWebsite::where('name','arabmediasociety')->value('id');
            $id2=Opreation::latest('updated_at')->value('id');
            if($id2==null){
                $id2=0;
            }
            Opreation::create([
                'website_id' => $ids,
                'user_id' => $userid
            ]);
            $data=[];
            $div2=$crawler2->filter('.post-listing article')->each(function($articles)use(&$data,$client2,&$id2,&$ids)
            {
                $innnerData =[];
                $article_detailes=$articles->children();
                $title=$article_detailes->eq(0)->text();
                $Disc= $article_detailes->eq(3)->text();
                $linkp= $article_detailes->filter('.post-box-title a')->first();
                $link=$linkp->attr('href');
                $linkc=$linkp->link();
                $art_page=$client2->click($linkc);
                $published_at=$art_page->filter(".tie-date")->text();
                $innnerData['title'] = $title;
                $innnerData['Discribtion'] = $Disc;
                $innnerData['link'] = $link;
                $innnerData['created_at']= date("Y-m-d");
                $innnerData['published_at']=date("Y-m-d", strtotime($published_at));
                $data[]=$innnerData;
                
                Article::create([
                    'art_link' => $link,
                    'published_at' => date("Y-m-d", strtotime($published_at)),
                    'title' => $title,
                    'disc' =>  $Disc,
                    'website_id' =>$ids,
                    'opr_id' => $id2 + 1
                ]);
            });
        }
        //$ido=ScrabedWebsite::where('name',$name)->value('id');
        //Article::where('website_id',$id)->latest('updated_at')->get(['art_link','title','disc','published_at','website_id','updated_at']);
        return $data;
    }

    public function re_scrabe_byId($id)
    {
        $wlink=ScrabedWebsite::where('id',$id)->value('link');
        $name=ScrabedWebsite::where('id',$id)->value('name');
        $userid = auth('sanctum')->user()->id;

        if ($name=='mklat')
        {
            $client = new Client();
            $crawler1 = $client->request('GET',$wlink);
            $name4=ScrabedWebsite::where('link',$wlink)->value('name');
            
            if(!$name4)
            {
                ScrabedWebsite::create([
                    'link'=>$wlink,
                    'name'=>'mklat',
                    'createddate'=> date("Y-m-d"),
                    'userid' => $userid
                ]);
            }
            $ids3=ScrabedWebsite::where('name','mklat')->value('id');
            $id=Opreation::latest('updated_at')->value('id');
            if($id==null){
                $id=0;
            }
            Opreation::create([
                'website_id' => $ids3,
                'user_id' => $userid
            ]);
            $data=[];
            $div = $crawler1->filter('.mag-box-container ul li ')->each(function($liElement)use(&$data,&$id,&$ids3)
            {
                if ($post_details=$liElement->filter('.post-details ')->count()>0)
                {
                $post_details=$liElement->filter('.post-details ')->children();
                $title= $post_details->eq(1)->text();
                $Disc= $post_details->eq(2)->text();
                $link= $post_details->filter('.post-title a')->first()->attr('href');
                $innnerData['title'] = $title;
                $innnerData['Discribtion'] = $Disc;
                $innnerData['link'] = $link;
                $innnerData['created_at']= date("Y-m-d");
                $data[]=$innnerData;
                
                Article::create([
                    'art_link' => $link,
                    'title' => $title,
                    'disc' =>  $Disc,
                    'website_id' =>$ids3,
                    'opr_id' => $id + 1
                ]);
                }
            });
        }
        elseif($name=='arabmediasociety')
        {
            
            $client2 = new Client();
            $crawler2= $client2->request('GET',$wlink);
            $name5=ScrabedWebsite::where('link',$wlink)->value('name');
            if(!$name5)
            {
                ScrabedWebsite::create([
                    'link'=>$wlink,
                    'name'=>'arabmediasociety',
                    'createddate'=> date("Y-m-d"),
                    'userid' => $userid
                ]);
            }
            $ids=ScrabedWebsite::where('name','arabmediasociety')->value('id');
            $id2=Opreation::latest('updated_at')->value('id');
            if($id2==null){
                $id2=0;
            }
            Opreation::create([
                'website_id' => $ids,
                'user_id' => $userid
            ]);
            $data=[];
            $div2=$crawler2->filter('.post-listing article')->each(function($articles)use(&$data,$client2,&$id2,&$ids)
            {
                $innnerData =[];
                $article_detailes=$articles->children();
                $title=$article_detailes->eq(0)->text();
                $Disc= $article_detailes->eq(3)->text();
                $linkp= $article_detailes->filter('.post-box-title a')->first();
                $link=$linkp->attr('href');
                $linkc=$linkp->link();
                $art_page=$client2->click($linkc);
                $published_at=$art_page->filter(".tie-date")->text();
                $innnerData['title'] = $title;
                $innnerData['Discribtion'] = $Disc;
                $innnerData['link'] = $link;
                $innnerData['created_at']= date("Y-m-d");
                $innnerData['published_at']=date("Y-m-d", strtotime($published_at));
                $data[]=$innnerData;
                
                Article::create([
                    'art_link' => $link,
                    'published_at' => date("Y-m-d", strtotime($published_at)),
                    'title' => $title,
                    'disc' =>  $Disc,
                    'website_id' =>$ids,
                    'opr_id' => $id2 + 1
                ]);
            });
        }
        //$ido=ScrabedWebsite::where('name',$name)->value('id');
        //Article::where('website_id',$id)->latest('updated_at')->get(['art_link','title','disc','published_at','website_id','updated_at']);
        return $data;
    }
    

    public function show_scarpedArticle_byWebsiteId($id)
    {
        return Article::join('scrabed_websites','articles.website_id','=','scrabed_websites.id')
        ->where('website_id',$id)
        ->orderBy('published_at','DESC')
        ->get(['articles.art_link','articles.title','articles.disc'
        ,'articles.published_at','scrabed_websites.name','articles.updated_at']);
    }

    public function logRequests(){
        
        return Activity::all();
    }

    public function allhistory(){
        
        return Opreation::join('users','opreations.user_id','=','users.id')
        ->get(['users.name as User name','opreations.created_at as Scraping date']);
    }
    
    }
