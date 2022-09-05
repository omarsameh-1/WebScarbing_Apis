<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use Illuminate\Support\Facades\Storage;
use App\Models\ScrabedWebsite;
use App\Models\Article;
use App\Models\Opreation;

class WebScraping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'web-scarping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */

    //logic
    public function handle()
    {
        $data=[];
        $client = new Client();
        $crawler1 = $client->request('GET','https://www.mklat.com/category/technology/computer-internet/');
        $name4=ScrabedWebsite::where('link','https://www.mklat.com/category/technology/computer-internet/')->value('name');
        if(!$name4)
        {
            ScrabedWebsite::create([
                'link'=>'https://www.mklat.com/category/technology/computer-internet/',
                'name'=>'mklat',
                'createddate'=> date("Y-m-d"),
                'userid' =>1
            ]);
        }

        $ids3=ScrabedWebsite::where('name','mklat')->value('id');
        $id=Opreation::latest('updated_at')->value('id');
        if($id==null){
            $id=0;
        }

        Opreation::create([
            'website_id' => $ids3,
            'user_id' =>1
        ]);

        $div = $crawler1->filter('.mag-box-container ul li ')->each(function($liElement)use(&$data,&$id,&$ids3){
            $innnerData =[];
            if ($post_details=$liElement->filter('.post-details ')->count()>0)
            {
            $post_details=$liElement->filter('.post-details ')->children();
            $title= $post_details->eq(1)->text();
            $Disc= $post_details->eq(2)->text();
            $link= $post_details->filter('.post-title a')->first()->attr('href');
            
            $innnerData['id']=1;
            $innnerData['website_link']="https://www.mklat.com/category/technology/computer-internet/";
            $innnerData['website_name']="mklat";
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

        $client2 = new Client();
        $crawler2= $client2->request('GET','https://www.arabmediasociety.com/category/features/');
        $name5=ScrabedWebsite::where('link','https://www.arabmediasociety.com/category/features/')->value('name');
        if(!$name5)
        {
            ScrabedWebsite::create([
                'link'=>'https://www.arabmediasociety.com/category/features/',
                'name'=>'arabmediasociety',
                'createddate'=> date("Y-m-d"),
                'userid' => 1
            ]);
        }


        $ids=ScrabedWebsite::where('name','arabmediasociety')->value('id');
        $id2=Opreation::latest('updated_at')->value('id');
        if($id2==null){
            $id2=0;
        }

        Opreation::create([
            'website_id' => $ids,
            'user_id' => 1
        ]);

        $div2=$crawler2->filter('.post-listing article')->each(function($articles)use(&$data,$client2,&$id2,&$ids){
            
            $innnerData =[];
            $article_detailes=$articles->children();
            $title=$article_detailes->eq(0)->text();
            $Disc= $article_detailes->eq(3)->text();
            $linkp= $article_detailes->filter('.post-box-title a')->first();

            $link=$linkp->attr('href');

            $linkc=$linkp->link();
            $art_page=$client2->click($linkc);

            $published_at=$art_page->filter(".tie-date")->text();
            
            $innnerData['id']=2;
            $innnerData['website_link']="https://www.arabmediasociety.com/category/features/";
            $innnerData['website_name']="arabmediasociety";
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

        //$data=json_encode($data,JSON_UNESCAPED_UNICODE);
        //Storage::disk('public')->put("ScrabedWebsites.json",$data);

        return 0;
    }
}
