    <footer id="colophon" class="site-footer bg-secondary text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            
            <!-- Main Footer Content -->
            <div class="grid lg:grid-cols-4 gap-8 mb-8">
                
                <!-- Platform Info -->
                <div class="lg:col-span-1">
                    <div class="flex items-center mb-4">
                        <?php if (has_custom_logo()): ?>
                            <?php the_custom_logo(); ?>
                        <?php else: ?>
                            <svg class="h-10 w-10 text-primary-light" viewBox="0 0 40 40" fill="currentColor">
                                <path d="M20 2C10.06 2 2 10.06 2 20s8.06 18 18 18 18-8.06 18-18S29.94 2 20 2zm0 32c-7.73 0-14-6.27-14-14S12.27 6 20 6s14 6.27 14 14-6.27 14-14 14z"/>
                                <path d="M20 10c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2s2-.9 2-2v-6c0-1.1-.9-2-2-2zm0 12c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                                <circle cx="20" cy="15" r="3" fill="white"/>
                            </svg>
                        <?php endif; ?>
                        <div class="ml-3">
                            <h3 class="text-xl font-inter font-bold"><?php bloginfo('name'); ?></h3>
                            <p class="text-sm text-gray-300"><?php _e('Medical Excellence Network', 'tn-medconnect'); ?></p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-6">
                        <?php _e('Connecting Tamil Nadu\'s medical professionals for excellence in healthcare through knowledge sharing, collaboration, and continuous learning.', 'tn-medconnect'); ?>
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="lg:col-span-1">
                    <h4 class="text-lg font-inter font-semibold mb-4"><?php _e('Quick Links', 'tn-medconnect'); ?></h4>
                    <ul class="space-y-2">
                        <li><a href="<?php echo esc_url(home_url('/knowledge-hub/')); ?>" class="text-gray-300 hover:text-white transition-colors duration-200"><?php _e('Knowledge Hub', 'tn-medconnect'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/case-studies/')); ?>" class="text-gray-300 hover:text-white transition-colors duration-200"><?php _e('Case Studies', 'tn-medconnect'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/cme-courses/')); ?>" class="text-gray-300 hover:text-white transition-colors duration-200"><?php _e('CME Courses', 'tn-medconnect'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/medical-resources/')); ?>" class="text-gray-300 hover:text-white transition-colors duration-200"><?php _e('Medical Resources', 'tn-medconnect'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/professional-networking/')); ?>" class="text-gray-300 hover:text-white transition-colors duration-200"><?php _e('Professional Networking', 'tn-medconnect'); ?></a></li>
                    </ul>
                </div>
                
                <!-- Medical Specialties -->
                <div class="lg:col-span-1">
                    <h4 class="text-lg font-inter font-semibold mb-4"><?php _e('Medical Specialties', 'tn-medconnect'); ?></h4>
                    <ul class="space-y-2">
                        <?php
                        $specialties = get_terms(array(
                            'taxonomy' => 'medical_specialty',
                            'hide_empty' => false,
                            'number' => 6,
                        ));
                        
                        if (!empty($specialties) && !is_wp_error($specialties)):
                            foreach ($specialties as $specialty):
                        ?>
                            <li><a href="<?php echo esc_url(get_term_link($specialty)); ?>" class="text-gray-300 hover:text-white transition-colors duration-200"><?php echo esc_html($specialty->name); ?></a></li>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                        <li><a href="<?php echo esc_url(home_url('/medical-specialties/')); ?>" class="text-primary-light hover:text-white transition-colors duration-200 font-semibold"><?php _e('View All Specialties →', 'tn-medconnect'); ?></a></li>
                    </ul>
                </div>
                
                <!-- Contact & Support -->
                <div class="lg:col-span-1">
                    <h4 class="text-lg font-inter font-semibold mb-4"><?php _e('Contact & Support', 'tn-medconnect'); ?></h4>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-300">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <a href="mailto:support@tnmedconnect.com" class="hover:text-white transition-colors duration-200">support@tnmedconnect.com</a>
                        </li>
                        <li class="flex items-center text-gray-300">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <a href="<?php echo esc_url(home_url('/help-center/')); ?>" class="hover:text-white transition-colors duration-200"><?php _e('Help Center', 'tn-medconnect'); ?></a>
                        </li>
                        <li class="flex items-center text-gray-300">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <a href="<?php echo esc_url(home_url('/faq/')); ?>" class="hover:text-white transition-colors duration-200"><?php _e('FAQ', 'tn-medconnect'); ?></a>
                        </li>
                        <li class="flex items-center text-gray-300">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <a href="<?php echo esc_url(home_url('/report-issue/')); ?>" class="hover:text-white transition-colors duration-200"><?php _e('Report an Issue', 'tn-medconnect'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-300 text-sm mb-4 md:mb-0">
                        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'tn-medconnect'); ?></p>
                        <p class="mt-1"><?php _e('Empowering Tamil Nadu\'s medical professionals for excellence in healthcare.', 'tn-medconnect'); ?></p>
                    </div>
                    
                    <div class="flex space-x-6 text-sm">
                        <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <?php _e('Privacy Policy', 'tn-medconnect'); ?>
                        </a>
                        <a href="<?php echo esc_url(home_url('/terms-of-service/')); ?>" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <?php _e('Terms of Service', 'tn-medconnect'); ?>
                        </a>
                        <a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>" class="text-gray-300 hover:text-white transition-colors duration-200">
                            <?php _e('Cookie Policy', 'tn-medconnect'); ?>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Medical Disclaimer -->
            <div class="mt-8 p-4 bg-gray-800 rounded-lg">
                <p class="text-xs text-gray-400 text-center">
                    <strong><?php _e('Medical Disclaimer:', 'tn-medconnect'); ?></strong> 
                    <?php _e('The information provided on this platform is for educational and professional development purposes only. It is not intended as medical advice and should not replace consultation with qualified healthcare professionals. Always consult with appropriate medical authorities for patient care decisions.', 'tn-medconnect'); ?>
                </p>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<!-- Back to Top Button -->
<button id="back-to-top" class="fixed bottom-8 right-8 bg-primary text-white p-3 rounded-full shadow-clinical-lg hover:bg-primary-dark transition-all duration-200 opacity-0 invisible">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
    </svg>
</button>

<!-- Mobile Menu JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Back to top button
    const backToTopButton = document.getElementById('back-to-top');
    
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible');
                backToTopButton.classList.remove('opacity-100', 'visible');
            }
        });
        
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Personalize dashboard functionality
    const personalizeButton = document.getElementById('personalize-dashboard');
    const specialtySelector = document.getElementById('specialty-selector');
    const careerStageSelector = document.getElementById('career-stage-selector');
    
    if (personalizeButton && specialtySelector && careerStageSelector) {
        personalizeButton.addEventListener('click', function() {
            const specialty = specialtySelector.value;
            const careerStage = careerStageSelector.value;
            
            if (specialty && careerStage) {
                // Store preferences in localStorage
                localStorage.setItem('tn_medconnect_specialty', specialty);
                localStorage.setItem('tn_medconnect_career_stage', careerStage);
                
                // Redirect to dashboard
                window.location.href = '<?php echo esc_url(home_url('/professional-dashboard/')); ?>';
            } else {
                alert('<?php _e('Please select both specialty and career stage to personalize your experience.', 'tn-medconnect'); ?>');
            }
        });
    }
});
</script>

<?php wp_footer(); ?>

</body>
</html> 