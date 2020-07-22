//родитель таскаемых
const tasksListElement = document.querySelector(`.tasks__list`);
//таскаемые
const taskElements = tasksListElement.querySelectorAll(`.tasks__item`);

/*По умолчанию большинство элементов не может перемещаться,
поэтому присвоим им атрибут draggable со значением true, чтобы изменить это поведение.*/
for (const task of taskElements) {
    task.draggable = true;
}

//selected если тащим
tasksListElement.addEventListener(`dragstart`, (evt) => {
    evt.target.classList.add(`selected`);
});

//убрали selected, если отпустили
tasksListElement.addEventListener(`dragend`, (evt) => {
    evt.target.classList.remove(`selected`);
});

const getNextElement = (cursorPosition, currentElement) => {
    const currentElementCoord = currentElement.getBoundingClientRect();

    //вычисляем центр элемента, над которым курсор
    const currentElementCenter = currentElementCoord.y + currentElementCoord.height / 2;

    //если выше центра, то перед ним. Иначе перед следующим
    const nextElement = (cursorPosition < currentElementCenter) ?
        currentElement :
        currentElement.nextElementSibling;

    return nextElement;

};

//Следим куда тащим
tasksListElement.addEventListener(`dragover`, (evt) => {
    evt.preventDefault();//Куда можно бросить элемент

    //Что бросаем
    const activeElement = tasksListElement.querySelector(`.selected`);

    //Куда бросаем
    const currentElement = evt.target;

    //Проверяем, что бросаем не на себя
    const isMoveable = activeElement !== currentElement &&
        currentElement.classList.contains(`tasks__item`);

    //Если на себя, всё отменяем.
    if (!isMoveable) {
        return;
    }

    //Определяем элемент, перед которым будет вставлен бросаемый
    const nextElement = getNextElement(evt.clientY, currentElement);

    //Убираем лишние срабатывания (ничего не делаем, если недотащили). Вставляем только если центр элемента пересекался курсором.
    if (
        nextElement &&
        activeElement === nextElement.previousElementSibling ||
        activeElement === nextElement
    ) {
        return;
    }

    //Вставляем куда надо
    tasksListElement.insertBefore(activeElement, nextElement);
    const group = activeElement.parentElement.childNodes;
    console.log(activeElement.parentElement.childNodes);
});