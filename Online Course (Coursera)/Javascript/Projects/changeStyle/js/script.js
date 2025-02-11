function closeMe(){
    x=document.getElementById("demo");
    //option 1: change the style directly
    //x.style.display='none';

    //option 2: change the class
    x.className="closed";
}
function openMe(){
    x=document.getElementById("demo");
    // option 1: change the style
    // x.style.display='block';

    //option 2: change the class
    x.className="open";

}