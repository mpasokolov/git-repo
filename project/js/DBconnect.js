
loadData(function (response) {
      response.forEach(function (item) {

        //создаем контейнер для товара
        var itemBlock = document.createElement('div');
        itemBlock.className = 'item';

        var itemLink = document.createElement('a');
        itemLink.className = 'item__link';
        itemLink.href = 'single_page.html';

        var itemImg = document.createElement('img');
        itemImg.className = 'item__img';
        itemImg.src = "img/item1.png";
        itemLink.alt = 'item1';

        var descriptionBlock = document.createElement('div');
        descriptionBlock.className = 'item__description';

        var itemName = document.createElement('p');
        itemName.className = "item__name";
        itemName.textContent = item.product_name;

        var itemPrice = document.createElement('span');
        itemPrice.className = "item__cost";
        itemPrice.textContent = '$' + item.price;

        var addToCartBlock = document.createElement('div');
        addToCartBlock.className = "item__add-to-cart-block";

        var addToCartButton = document.createElement('button');
        addToCartButton.className = "item__add-to-cart";
        addToCartButton.innerHTML = 'Add to&nbsp;Cart';

        var addToCart = document.createElement('i');
        addToCart.className = "fa fa-shopping-cart item__arrow";
        addToCart.setAttribute('aria-hidden', 'true');

        addToCartButton.appendChild(addToCart);
        addToCartBlock.appendChild(addToCartButton);

        descriptionBlock.appendChild(itemName);
        descriptionBlock.appendChild(itemPrice);

        itemLink.appendChild(itemImg);
        itemLink.appendChild(descriptionBlock);

        itemBlock.appendChild(itemLink);
        itemBlock.appendChild(addToCartBlock);

        document.getElementById('featured-items__list').appendChild(itemBlock);
      });
  });


function loadData(callback) {
  $.get("https://raw.githubusercontent.com/GeekBrainsTutorial/online-store-api/master/responses/catalogData.json",
    function (response) {
      callback(response);
    }, 'json'
    )
}

/*
                <div class="item">
                    <a class="item__link" href="single_page.html">
                        <img class="item__img" src="img/item1.png" alt="item1">
                        <div class="item__description">
                            <p class="item__name">Mango People T-shirt</p>
                            <span class="item__cost">$52.00</span>
                        </div>
                    </a>
                    <div class="item__add-to-cart-block">
                        <button class="item__add-to-cart"><i class="fa fa-shopping-cart item__arrow" aria-hidden="true"></i>Add to&nbsp;Cart</button>
                    </div>
                </div>
 */