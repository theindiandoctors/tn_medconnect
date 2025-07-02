<?php
/**
 * The main template file
 *
 * @package TN_MedConnect
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <?php if (is_home() && !is_front_page()): ?>
            <header class="page-header mb-8">
                <h1 class="page-title text-3xl font-inter font-bold text-secondary">
                    <?php single_post_title(); ?>
                </h1>
            </header>
        <?php endif; ?>

        <?php if (have_posts()): ?>
            
            <!-- Hero Section for Homepage -->
            <?php if (is_home() && is_front_page()): ?>
                <section class="relative bg-gradient-to-br from-primary-50 to-primary-100 overflow-hidden mb-12">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary/10 to-transparent"></div>
                    <div class="relative py-24 lg:py-32">
                        <div class="grid lg:grid-cols-2 gap-12 items-center">
                            <div class="space-y-8">
                                <div class="space-y-4">
                                    <h1 class="text-4xl lg:text-6xl font-inter font-bold text-secondary leading-tight">
                                        Empowering Tamil Nadu's 
                                        <span class="text-gradient">Medical Excellence</span>
                                    </h1>
                                    <p class="text-xl text-text-secondary leading-relaxed">
                                        Through Connected Knowledge - Join Tamil Nadu's premier medical community platform fostering collaboration, continuous learning, and professional growth.
                                    </p>
                                </div>

                                <!-- Specialty Selection -->
                                <div class="bg-white rounded-lg shadow-clinical p-6 border border-border">
                                    <h3 class="text-lg font-inter font-semibold text-secondary mb-4"><?php _e('Personalize Your Experience', 'tn-medconnect'); ?></h3>
                                    <div class="grid sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-text-secondary mb-2"><?php _e('Medical Specialty', 'tn-medconnect'); ?></label>
                                            <select name="specialty" class="input-field" id="specialty-selector">
                                                <option value=""><?php _e('Select Specialty', 'tn-medconnect'); ?></option>
                                                <option value="cardiology"><?php _e('Cardiology', 'tn-medconnect'); ?></option>
                                                <option value="orthopedics"><?php _e('Orthopedics', 'tn-medconnect'); ?></option>
                                                <option value="pediatrics"><?php _e('Pediatrics', 'tn-medconnect'); ?></option>
                                                <option value="internal-medicine"><?php _e('Internal Medicine', 'tn-medconnect'); ?></option>
                                                <option value="surgery"><?php _e('Surgery', 'tn-medconnect'); ?></option>
                                                <option value="radiology"><?php _e('Radiology', 'tn-medconnect'); ?></option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-text-secondary mb-2"><?php _e('Career Stage', 'tn-medconnect'); ?></label>
                                            <select name="career_stage" class="input-field" id="career-stage-selector">
                                                <option value=""><?php _e('Select Stage', 'tn-medconnect'); ?></option>
                                                <option value="medical-student"><?php _e('Medical Student', 'tn-medconnect'); ?></option>
                                                <option value="resident"><?php _e('Resident', 'tn-medconnect'); ?></option>
                                                <option value="practicing-physician"><?php _e('Practicing Physician', 'tn-medconnect'); ?></option>
                                                <option value="academic"><?php _e('Academic', 'tn-medconnect'); ?></option>
                                                <option value="administrator"><?php _e('Administrator', 'tn-medconnect'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <button class="btn-primary w-full mt-4" id="personalize-dashboard">
                                        <?php _e('Access Personalized Dashboard', 'tn-medconnect'); ?>
                                    </button>
                                </div>
                            </div>

                            <div class="relative">
                                <div class="aspect-w-4 aspect-h-3 rounded-lg overflow-hidden shadow-clinical-lg">
                                    <?php if (has_custom_logo()): ?>
                                        <?php the_custom_logo(); ?>
                                    <?php else: ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/medical-professionals.jpg" 
                                             alt="<?php _e('Tamil Nadu Medical Professionals', 'tn-medconnect'); ?>" 
                                             class="w-full h-full object-cover" />
                                    <?php endif; ?>
                                </div>
                                <!-- Floating Stats -->
                                <div class="absolute -bottom-6 -left-6 bg-white rounded-lg shadow-clinical p-4 border border-border">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-12 h-12 bg-success-100 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-success" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-2xl font-inter font-bold text-secondary">
                                                <?php echo esc_html(get_theme_mod('tn_medconnect_total_professionals', '15,000+')); ?>+
                                            </p>
                                            <p class="text-sm text-text-secondary"><?php _e('Active Members', 'tn-medconnect'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Platform Statistics -->
                <section class="py-16 bg-white mb-12">
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="text-center">
                            <div class="text-3xl font-inter font-bold text-primary mb-2">
                                <?php echo esc_html(get_theme_mod('tn_medconnect_total_professionals', '15,000+')); ?>+
                            </div>
                            <div class="text-text-secondary"><?php _e('Medical Professionals', 'tn-medconnect'); ?></div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-inter font-bold text-primary mb-2">
                                <?php echo esc_html(get_theme_mod('tn_medconnect_total_articles', '2,500+')); ?>+
                            </div>
                            <div class="text-text-secondary"><?php _e('Knowledge Articles', 'tn-medconnect'); ?></div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-inter font-bold text-primary mb-2">
                                <?php echo esc_html(get_theme_mod('tn_medconnect_total_cme_hours', '50,000+')); ?>+
                            </div>
                            <div class="text-text-secondary"><?php _e('CME Hours Completed', 'tn-medconnect'); ?></div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-inter font-bold text-primary mb-2">25+</div>
                            <div class="text-text-secondary"><?php _e('Medical Specialties', 'tn-medconnect'); ?></div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Content Grid -->
            <div class="grid lg:grid-cols-3 gap-8">
                
                <!-- Main Content Area -->
                <div class="lg:col-span-2">
                    <div class="space-y-8">
                        <?php while (have_posts()): the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('knowledge-article card-hover'); ?>>
                                
                                <!-- Featured Image -->
                                <?php if (has_post_thumbnail()): ?>
                                    <div class="mb-6">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('large', array('class' => 'w-full h-64 object-cover rounded-lg')); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <!-- Article Meta -->
                                <div class="article-meta">
                                    <span class="text-primary font-semibold">
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span class="text-text-secondary">
                                        <?php the_author(); ?>
                                    </span>
                                    <?php if (has_category()): ?>
                                        <span class="mx-2">•</span>
                                        <span class="text-text-secondary">
                                            <?php the_category(', '); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Article Title -->
                                <h2 class="text-2xl font-inter font-bold text-secondary mb-4">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-200">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>

                                <!-- Article Excerpt -->
                                <div class="text-text-secondary mb-6">
                                    <?php the_excerpt(); ?>
                                </div>

                                <!-- Article Footer -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <?php if (has_tag()): ?>
                                            <div class="flex flex-wrap gap-2">
                                                <?php
                                                $tags = get_the_tags();
                                                if ($tags) {
                                                    foreach ($tags as $tag) {
                                                        echo '<span class="specialty-badge bg-primary-100 text-primary-800">' . esc_html($tag->name) . '</span>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary font-semibold hover:text-primary-700 transition-colors duration-200">
                                        <?php _e('Read More', 'tn-medconnect'); ?>
                                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"/>
                                        </svg>
                                    </a>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if (get_next_posts_link() || get_previous_posts_link()): ?>
                        <nav class="pagination mt-12">
                            <div class="flex justify-between items-center">
                                <div class="prev-posts">
                                    <?php previous_posts_link(__('← Previous', 'tn-medconnect')); ?>
                                </div>
                                <div class="next-posts">
                                    <?php next_posts_link(__('Next →', 'tn-medconnect')); ?>
                                </div>
                            </div>
                        </nav>
                    <?php endif; ?>

                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <?php get_sidebar(); ?>
                </div>

            </div>

        <?php else: ?>
            <!-- No Posts Found -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-primary" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-inter font-bold text-secondary mb-4">
                    <?php _e('No Content Found', 'tn-medconnect'); ?>
                </h2>
                <p class="text-text-secondary mb-8">
                    <?php _e('It looks like there are no posts yet. Check back soon for medical content and resources.', 'tn-medconnect'); ?>
                </p>
                <a href="<?php echo home_url(); ?>" class="btn-primary">
                    <?php _e('Return to Homepage', 'tn-medconnect'); ?>
                </a>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer(); 