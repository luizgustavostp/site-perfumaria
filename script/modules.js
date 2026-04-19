export async function updateitems() {
    if (!localStorage.getItem("catalog")) {
        try {
            const response = await fetch("produtos.php");
            const data = await response.json();
            localStorage.setItem("catalog", JSON.stringify(data));
            return data;
        } catch (erro) {
            console.error(erro);
            return [];
        }
        }
    else {
        return JSON.parse(localStorage.getItem("catalog"))
    }
}
export async function displayupdate(btn1,btn2,displayurl) {
    let hamburguerbtn = document.getElementById(btn1)
    let quitbtn = document.getElementById(btn2)
    let blur = document.getElementById("blur")
    let logged
    const me = await fetch("me.php").then(res => res.json()).then(res => {
        if (res.logged) {
            document.querySelector("#no_account").style.display = "none"
            document.querySelector("#on_account").style.display = "block"
        }
        else {
            document.querySelector("#no_account").style.display = "block"
            document.querySelector("#on_account").style.display = "none"
        }
        logged = res
    })
    document.querySelector("#logoutbtn").addEventListener("click",async (event) => {
        document.querySelector("#logoutform").submit()
    })
    console.log("depois da fetch")
    hamburguerbtn.addEventListener("click",() => {
        let display = document.getElementById(displayurl)
        let displaystyle = window.getComputedStyle(display).display
        if (displaystyle == "none") {
            display.style.display = "block"
        }
        else {
            display.style.display = "none"
        }
    })
    quitbtn.addEventListener("click",() => {
        let display = document.getElementById(displayurl)
        let displaystyle = window.getComputedStyle(display).display
        if (displaystyle == "none") {
            display.style.display = "block"
        }
        else {
            display.style.display = "none"
        }
    })
    return logged
}

export function carregardisplays(element,grido,itembase) {
    let newitem = itembase.cloneNode(true)
    let itembasechild = newitem.childNodes
    itembasechild[7].textContent += element.price.toString().replace('.', ',');
    itembasechild[9].textContent += element.price / 10
    itembasechild[9].textContent += "0"
    itembasechild[1].src = element.imagepath;
    itembasechild[3].textContent = element.name;
    itembasechild[13].src = element.starspath;
    itembasechild[11].href = `item.html?id=${element.id}`
    newitem.addEventListener("click",() => {
        window.location = `item.html?id=${element.id}`
    })
    grido.appendChild(newitem)
}

export function ativarmenuconfig(displayid,btn1,btn2) {
    function mudarwall() {
    const blur = document.querySelector("#blur")
    let filterdisplay = document.getElementById(displayid)
    let displaystyle = window.getComputedStyle(filterdisplay).display
    if (displaystyle == "none") {
        blur.style.display = "block"
        blur.style.visibility = "visible"
        filterdisplay.style.display = "block"
    }
    else {
        blur.style.display = "none"
        blur.style.visibility = "hidden"
        filterdisplay.style.display = "none"
    }
    }
    document.getElementById(btn1).addEventListener("click",() => {
        mudarwall(displayid)
    })
    document.getElementById(btn2).addEventListener("click",() => {
        mudarwall(displayid)
    })
}
export function erro(msg,vis,el) {
    const blurr = document.getElementById("blur")
    el.style.display = "flex"
    blurr.style.visibility = "visible"
    document.querySelector("#errormsg").textContent = msg
    document.querySelector("#errormsgs").textContent = msg
    el.style.visibility = vis

}