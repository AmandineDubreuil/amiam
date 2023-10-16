const tabs = [
    {
      tab: document.querySelector("#ajuste"),
      frame: document.querySelector("#tabs-1"),
      isVisible: true,
    },
    {
      tab: document.querySelector("#recette"),
      frame: document.querySelector("#tabs-2"),
      isVisible: false,
    },
   
  ];
  
  tabs.forEach((value) => {
    console.dir(value.tab);
    value.tab.addEventListener("click", () => {
      console.log("hello world");
  
      // 1ère étape faire disparaitre toutes mes frame
      for (let i = 0; i < tabs.length; i++) {
        tabs[i].frame.style.display = "none";
     //   console.dir(tabs[i].tab.className);
        tabs[i].tab.className="";
        tabs[i].isVisible = false
      }
      //2ème étape faire apparaitre la frame voulue
      value.frame.style.display = "block";
      value.isVisible = true;
      value.tab.className="selected";
    });
  });
  
  