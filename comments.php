<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<div id="comments" class="comments-area" style="margin-top:48px;">

    <?php if ($this->allow('comment')): ?>

    <h3 class="comment-title" style="font-size:17px;font-weight:700;margin:0 0 6px;"><?php $this->commentsNum(_t('暂无评论'), _t('1 条评论'), _t('%d 条评论')); ?></h3>

    <div id="comment-list" class="comment-list">
        <?php $this->comments()->to($comments); ?>
        <?php if ($comments->have()): ?>
            <?php while ($comments->next()): ?>
            <div class="comment-body" id="comment-<?php $comments->theId(); ?>">
                <div class="comment-meta">
                    <?php echo themeCommentAvatar($comments->mail, 40); ?>
                    <div>
                        <span class="c-author"><?php $comments->author(); ?></span>
                        <div class="c-time"><?php $comments->date('Y-m-d H:i'); ?></div>
                    </div>
                </div>
                <div class="c-content"><?php $comments->content(); ?></div>
                <a class="c-reply" href="<?php echo $comments->parentContent->permalink; ?>?replyTo=<?php echo $comments->coid; ?>#<?php $this->respondId(); ?>">回复</a>
            </div>
            <?php endwhile; ?>
            <?php $comments->pageNav('«', '»', 3, '...', array('wrapClass' => 'pagination', 'currentClass' => 'current')); ?>
        <?php else: ?>
            <p class="search-empty">还没有评论，快来抢沙发吧。</p>
        <?php endif; ?>
    </div>

    <!-- 评论表单 -->
    <div id="<?php $this->respondId(); ?>" class="comment-form">
        <div class="cancel-reply" style="margin-bottom:8px;">
            <?php $comments->cancelReply(_t('取消回复')); ?>
        </div>
        <h3 class="comment-title" style="font-size:17px;font-weight:700;margin:0 0 14px;">发表评论</h3>
        <form method="post" action="<?php $this->commentUrl(); ?>">
            <?php if ($this->user->hasLogin()): ?>
            <p class="logged-in-as" style="font-size:13px;color:#5b6068;margin:0 0 14px;">
                <?php _e('登录身份：'); ?><a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>
                。<a href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出'); ?> »</a>
            </p>
            <?php else: ?>
            <div class="form-row">
                <div>
                    <label for="author"><?php _e('称呼 *'); ?></label>
                    <input type="text" name="author" id="author" value="<?php $this->remember('author'); ?>" required>
                </div>
                <div>
                    <label for="mail"><?php _e('邮箱 *'); ?></label>
                    <input type="email" name="mail" id="mail" value="<?php $this->remember('mail'); ?>" required>
                </div>
            </div>
            <div>
                <label for="url"><?php _e('网址'); ?></label>
                <input type="url" name="url" id="url" value="<?php $this->remember('url'); ?>">
            </div>
            <?php endif; ?>
            <div>
                <label for="textarea"><?php _e('评论内容 *'); ?></label>
                <textarea name="text" id="textarea" required><?php $this->remember('text'); ?></textarea>
            </div>
            <button type="submit" class="submit-btn">
                <!-- IconPark: send -->
                <svg width="15" height="15" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M6 24L42 8 30 40l-6-13-13-6Z" stroke="currentColor" stroke-width="4" stroke-linejoin="round"/><path d="M30 40l6-16L24 24" stroke="currentColor" stroke-width="4" stroke-linejoin="round"/></svg>
                提交评论
            </button>
        </form>
    </div>

    <?php else: ?>
        <p class="search-empty">评论已关闭。</p>
    <?php endif; ?>
</div>
