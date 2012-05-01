<div id="utilityBarWrapper">
   <div id="utilityBar" class="ddsmoothmenu">
                 <ul>
                  <li>
<!-- Begin populating Profile links -->
                     <a href="#">Login</a>

                     <ul>
                        <li>
                          <?php echo $this -> Html -> link(__('Login', true), array(
                              'controller' => 'users',
                              'action' => 'login'
                           ));
                           ?>
<!--                            <a href="#">Sub Nav Link</a> -->
                        </li>
                     </ul>
                  </li>
                  </ul>
<!-- End populating Profile links -->
      
   </div>
</div>
