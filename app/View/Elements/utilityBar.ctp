<div id="utilityBarWrapper" class='ddsmoothmenu'>
   <div id="utilityBar" >
                 <ul>
                  <li>
<!-- Begin populating Profile links -->
                     <a href="#">My Account</a>

                     <ul>
                        <li>
                           <?php echo $this -> Html -> link(__('Login', true), array(
                              'controller' => 'users',
                              'action' => 'login'
                           ));
                           ?>
                        </li>
                     </ul>
                  </li>
                  </ul>
<!-- End populating Profile links -->
      
   </div>
</div>
