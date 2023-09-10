const hoverAreas = document.querySelectorAll(".sekcjaOfertowa")
const tables = Array.from(document.querySelectorAll(".tableBox"))

hoverAreas.forEach(area => {
    area.addEventListener("mouseenter", e => {

        const id = e.target.getAttribute('id')
        console.log(id)

        tables.forEach(table => {
            table.classList.add("tableHidden")
        })

        const toDisplay = tables.find(x => x.id === id)
        toDisplay.classList.remove("tableHidden")
        
        
    })
})

