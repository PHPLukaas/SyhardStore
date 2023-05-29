let links = document.querySelector("[data-delete]");

//boucle sur les liens
for (let link of links) {
    //on met un ecouteur d'événement
    link.addEventListener("click", function (e)
    {
        //Empeche la navigation
     e.preventDefault();
     //on demande la confirmation
        if(confirm("Voulez-vous supprimer cette image ?")){
            //Envoie de la requete ajax
            fetch(this.getAttribute("href"),{
                method: "DELETE" ,
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({"_token": this.dataset.token})
            }).then(response => response.json()).then(data =>{
                if(data.success){
                    this.parentElement.remove();
                }else{
                    alert(data.error)
                }

            })

        }
    });
}