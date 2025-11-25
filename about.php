<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>TechBuzz - All in one educational platform</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/main.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
   <!-- favicon link  -->
   <link rel="icon" href="images/favicon.png" type="image/x-icon">

</head>
<body>
<?php include 'components/user_header.php'; ?>

<section class="about_achievements" id="about">
   <div class="container about_achivements-container">
      <div class="about_achivements-left">
         <img src="images/about achievements.svg" alt="">
      </div>
      <div class="about_achievements-right">
         <h1>Achievements</h1>
         <p>Welcome to TecchBuzz! We are passionate tech enthusiasts dedicated to exploring the ever-evolving world of technology. With a solid foundation in [mention relevant education or background], I’ve had the privilege of working on numerous projects and initiatives that have made a meaningful impact in the tech sphere. Some of my notable achievements include [mention specific projects, innovations, or contributions].

These experiences have not only sharpened my technical expertise in [list technical skills] but also strengthened my commitment to innovation and problem-solving. Beyond the lines of code and circuits, I believe in the transformative power of technology to shape our future.

TecchBuzz is my platform to share insights, tutorials, and reviews designed to inspire and empower fellow tech enthusiasts. Join me on this exciting journey—and let’s embark on a tech-filled adventure together!</p>
         <div class="acheiments_cards">
            <article class="acheiment_cards">
               <span class="acheiments_icon">
                  <i class="uil uil-notebooks"></i>
               </span>
               <h3>100+</h3>
               <p>Courses</p>
            </article>
            <article class="acheiment_cards">
               <span class="acheiments_icon">
                  <i class="uil uil-book-reader"></i>
               </span>
               <h3>3k+</h3>
               <p>Students</p>
            </article>
            <article class="acheiment_cards">
               <span class="acheiments_icon">
                  <i class="uil uil-award"></i>
               </span>
               <h3>450+</h3>
               <p>Awards</p>
            </article>
            
         </div>
      </div>
      </div>
   </div>
</section>





<section class="faqs">
  <h2>Frequently Asked Questions</h2> 
  <div class="container faqs_container">

    <article class="faq">
      <div class="faq_icon"><i class="uil uil-plus"></i></div>
      <div class="question_answer">
        <h4>How do I make a purchase on your website?</h4>
        <p>All the courses are free so there is no need to purchase it — you only have to add and learn.</p>
      </div>
    </article>

    <article class="faq">
      <div class="faq_icon"><i class="uil uil-plus"></i></div>
      <div class="question_answer">
        <h4>Can I access the content on multiple devices?</h4>
        <p>Yes! The content will be accessible on multiple devices at the same time.</p>
      </div>
    </article>

    <article class="faq">
      <div class="faq_icon"><i class="uil uil-plus"></i></div>
      <div class="question_answer">
        <h4>Do you offer a mobile app for offline access?</h4>
        <p>Currently we don't offer a mobile app for offline access.</p>
      </div>
    </article>

    <article class="faq">
      <div class="faq_icon"><i class="uil uil-plus"></i></div>
      <div class="question_answer">
        <h4>How often is the content updated?</h4>
        <p>The contents are updated every 2–3 months to keep everything fresh and relevant.</p>
      </div>
    </article>

    <article class="faq">
      <div class="faq_icon"><i class="uil uil-plus"></i></div>
      <div class="question_answer">
        <h4>Can I download videos or course materials for offline use?</h4>
        <p>Yes, you can download the videos or course materials for offline use. Downloads last for 6 months, after which you can re-download them.</p>
      </div>
    </article>

    <article class="faq">
      <div class="faq_icon"><i class="uil uil-plus"></i></div>
      <div class="question_answer">
        <h4>Are there any system requirements for using your website?</h4>
        <p>There are no specific system requirements, but we recommend avoiding devices running very old versions of browsers or operating systems.</p>
      </div>
    </article>

    <article class="faq">
      <div class="faq_icon"><i class="uil uil-plus"></i></div>
      <div class="question_answer">
        <h4>Is there any payment gateway on your website?</h4>
        <p>No, there is no payment gateway. You just need to sign in and start learning!</p>
      </div>
    </article>

    <article class="faq">
      <div class="faq_icon"><i class="uil uil-plus"></i></div>
      <div class="question_answer">
        <h4>How can I provide feedback on the website?</h4>
        <p>There is a section where you can give feedback by filling in your details. If your feedback follows all community guidelines, it will be displayed on our website. You can also report issues in the report section.</p>
      </div>
    </article>

  </div>
</section>

<script>
// Toggle FAQ open/close
const faqs = document.querySelectorAll('.faq');

faqs.forEach(faq => {
  faq.addEventListener('click', () => {
    // Close all other FAQs before opening the clicked one
    faqs.forEach(item => {
      if (item !== faq) item.classList.remove('open');
    });
    faq.classList.toggle('open');
  });
});
</script>

<section class="container testimonials_container mySwiper">
   <h2>
      Students' Testimonials
   </h2>
   <div class=" test swiper-wrapper">
      <article class="testimonial swiper-slide">
         <div class="avatar">
            <img src="images\rahul.webp" >
         </div>
         <div class="testimonial_info">
            <h5> Rahul</h5>
            <small>Student</small>
         </div>
         <div class="testimonial_body">
            <p>
            I love your e-learning website! The user-freindly interface makes it so easy to navigate and the content is top-notch. It's been game changer for my learning journey
            </p>
         </div>
      </article>
      <article class="testimonial swiper-slide">
         <div class="avatar">
            <img src="images/mahesh.jpg" alt="">
         </div>
         <div class="testimonial_info">
            <h5>Mahesh</h5>
            <small>Student</small>
         </div>
         <div class="testimonial_body">
            <p>
            I have tried several e-learning platforms but Tech-Buzz stands out. The user freindly interface, diverse course offerings and excellent support make it my go-to-choice
            </p>
         </div>
      </article>
      <article class="testimonial swiper-slide">
         <div class="avatar">
            <img src="images/hasmat.jpg" alt="">
         </div>
         <div class="testimonial_info">
            <h5>Hasmat</h5>
            <small>Student</small>
         </div>
         <div class="testimonial_body">
            <p>
            I appreciate how Tech-Buzz keeps adding new courses and updates existing ones to stay relevant. It shows their commitment to providing the best learning experience
            </p>
         </div>
      </article>
 
      <article class="testimonial swiper-slide">
         <div class="avatar">
            <img src="images/junaid.jpg" alt="">
         </div>
         <div class="testimonial_info">
            <h5>Junaid</h5>
            <small>Student</small>
         </div>
         <div class="testimonial_body">
            <p>
            Consider adding more advanced search filters to help users find courses that match their specific needs and skill levels, it will also make the website more user friendly and structured.

            </p>
         </div>
      </article>

      
   </div>
   <div class="swiper-pagination"></div>
</section>  ( add a arrow button to swipe the testimonial )


<!-- footer section starts  -->
<?php include 'components/footer.php'; ?>
<!-- footer section ends -->
<script src="js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<script>
    var swiper = new Swiper(".mySwiper", {
      slidesPerView: 1,
      spaceBetween: 30,      
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
        dynamicBullets: true,
      },
      breakpoints: {
         600: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        }
      }
    });
  </script>
  <script>(function(w, d) { w.CollectId = "68da8be0fb32e97f0f6b1877"; var h = d.head || d.getElementsByTagName("head")[0]; var s = d.createElement("script"); s.setAttribute("type", "text/javascript"); s.async=true; s.setAttribute("src", "https://collectcdn.com/launcher.js"); h.appendChild(s); })(window, document);</script>
</body>
</html>