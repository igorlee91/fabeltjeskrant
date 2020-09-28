window.onload = () =>{
    class Topbarview{
        constructor(){
            //header
            this.avatar=document.querySelector(".icon_profile");
            this.bell=document.querySelector("#bell");
            this.hamburger = document.querySelector("#hamburger");
            this.usermenu = this.getel(".usermenu");
            this.notifmenu = this.getel(".notifmenu");
            //main
            //f
            this.displaymenu(this.hamburger,this.usermenu);
            this.displaymenu(this.bell,this.notifmenu);
        }
        displaymenu(trigger,targetmenu){
            trigger.addEventListener("click", ()=>{
                if(!targetmenu.classList.contains("undisplayed")){
                    targetmenu.classList.add("undisplayed");
                }else{
                    targetmenu.classList.remove("undisplayed");
                }
                window.addEventListener("click",x=>{
                    if (x.target!==trigger) {
                        if (!targetmenu.classList.contains("undisplayed")) {
                            targetmenu.classList.add("undisplayed")
                        } 
                    } 
                })
            })
        }

        create(tag,cl){
            const el = document.createElement(tag);
            if(cl){
                el.classList.add(cl);
            }
            return el;
        }
        getel(tag){
            return document.querySelector(tag);
        }
    }
    class Topbar{
        constructor(view){
            this.view=view;
        }
    }
    const topbar = new Topbar(new Topbarview);
}