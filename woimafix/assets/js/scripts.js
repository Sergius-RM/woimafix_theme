jQuery.noConflict();


window.addEventListener('scroll', function() {
    var header = document.querySelector('.header_area');
    if (window.scrollY > 140) {
        header.classList.add('header_sticky');
    } else {
        header.classList.remove('header_sticky');
    }
});


(function ($) {
  document.addEventListener('DOMContentLoaded', function () {
    const topLevelItems = document.querySelectorAll('.menu-item-has-children');

    topLevelItems.forEach(function (topLevelItem) {
        const subMenus = topLevelItem.querySelectorAll('.sub-menu');
        const triggerElement = topLevelItem.querySelector('.menu-trigger'); // Находим .menu-trigger внутри пункта меню

        topLevelItem.addEventListener('click', function (event) {
            const target = event.target;

            // Также проверяем, что target это пункт меню с классом menu-trigger
            if (target === triggerElement || topLevelItem.classList.contains('menu-trigger')) {
                event.preventDefault();

                if (window.innerWidth <= 845) {
                    const isSubMenuOpen = subMenus[0].style.display === 'block';

                    closeOtherSubMenus(topLevelItem);

                    if (isSubMenuOpen) {
                        subMenus.forEach(function (subMenu) {
                            subMenu.style.display = 'none';
                        });
                    } else {
                        subMenus.forEach(function (subMenu) {
                            subMenu.style.display = 'block';
                        });
                    }
                }
            }
        }, { passive: true });

        topLevelItem.addEventListener('mouseenter', function (event) {
            if (window.innerWidth > 845) {
                const isSubMenuOpen = subMenus[0].style.display === 'block';

                closeOtherSubMenus(topLevelItem);

                if (!isSubMenuOpen) {
                    subMenus.forEach(function (subMenu) {
                        subMenu.style.display = 'block';
                    });
                }
            }
        }, { passive: true });

        subMenus.forEach(function (subMenu) {
            subMenu.style.display = 'none';
        });
    });

    function closeOtherSubMenus(currentTopLevelItem) {
        topLevelItems.forEach(function (item) {
            if (item !== currentTopLevelItem) {
                const subMenus = item.querySelectorAll('.sub-menu');
                subMenus.forEach(function (subMenu) {
                    subMenu.style.display = 'none';
                });
            }
        });
    }

}, { passive: true });
 // Добавляем флаг passive


  $('.pricing_item .spoiler_link').click(function () {
    const $priceContent = $(this).closest('.pricing_item').find('.price_content');
    const $triggerElement = $(this);

    $priceContent.slideToggle();
    $triggerElement.toggleClass('rotate');
  });

 ///tiny-slider
 $('.slide_list').each(function (index, element) {
    var slider = tns({
      container: element,
      autoHeight: true,
      items: 1,
      loop: true,
      swipeAngle: false,
      speed: 500,
      mouseDrag: true,
      autoplay: true,
      controls: true,
      nav: false,
      responsive: {
        540: {
          items: 1
        },
        1200: {
          items: 1
        },
        1440: {
          items: 1
        },
        1500: {
          items: 1
        },
      }
    });
  });

})(jQuery);
