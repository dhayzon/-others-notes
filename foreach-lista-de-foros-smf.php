	foreach ($context['categories'] as $category)
	{
		// If theres no parent boards we can see, avoid showing an empty category (unless its collapsed)
		if (empty($category['boards']) && !$category['is_collapsed'])
			continue;

		echo '
			<div class="uList-item">
			<div class="uList-header" id="category_', $category['id'], '">
							<h2 class="catbg">';

		// If this category even can collapse, show a link to collapse it.
		if ($category['can_collapse'])
			echo '
								<a class="collapse floatright" href="', $category['collapse_href'], '">', $category['collapse_image'], '</a>';

		if (!$context['user']['is_guest'] && !empty($category['show_unread']))
			echo '
								<a class="unreadlink" href="', $scripturl, '?action=unread;c=', $category['id'], '">', $txt['view_unread_category'], '</a>';

		echo '
								', $category['link'], '
							</h2>
			</div>';

		// Assuming the category hasn't been collapsed...
		if (!$category['is_collapsed'])
		{

			echo '
				<ul class="uList-body" id="category_', $category['id'], '_boards">';
				/* Each board in each category's boards has:
				new (is it new?), id, name, description, moderators (see below), link_moderators (just a list.),
				children (see below.), link_children (easier to use.), children_new (are they new?),
				topics (# of), posts (# of), link, href, and last_post. (see below.) */
				foreach ($category['boards'] as $board)
				{
					echo '
					<li id="board_', $board['id'], '" ',!empty($board['children'])? 'class="uHchildren"':'','>';

					echo '
						<div class="info">
						    ',!empty($board['children'])? '<span class="children floatleft"><i class="icon-down-small"></i></span>':'','
							<a class="subject" href="', $board['href'], '" id="b', $board['id'], '">', $board['name'], '</a>';

					// Has it outstanding posts for approval?
					if ($board['can_approve_posts'] && ($board['unapproved_posts'] || $board['unapproved_topics']))
						echo '
							<a href="', $scripturl, '?action=moderate;area=postmod;sa=', ($board['unapproved_topics'] > 0 ? 'topics' : 'posts'), ';brd=', $board['id'], ';', $context['session_var'], '=', $context['session_id'], '" title="', sprintf($txt['unapproved_posts'], $board['unapproved_topics'], $board['unapproved_posts']), '" class="moderation_link">(!)</a>';

					// Show some basic information about the number of posts, etc.
						echo '
						
	 						<span class="floatright">
	 						',$board['is_redirect'] ? comma_format($board['posts']) : comma_format($board['topics']),' ', $board['is_redirect'] ? $txt['redirects'] : $txt['posts'], '

							</span>
	 					 </div>';
					
					// Show the "Child Boards: ". (there's a link_children but we're going to bold the new ones...)
					if (!empty($board['children']))
					{
						// Sort the links into an array with new boards bold so it can be imploded.
						$children = array();
						/* Each child in each board's children has:
								id, name, description, new (is it new?), topics (#), posts (#), href, link, and last_post. */
						foreach ($board['children'] as $child)
						{
							if (!$child['is_redirect'])
								$child['link'] = '<a href="' . $child['href'] . '" ' . ($child['new'] ? 'class="new_posts" ' : '') . 'title="' . ($child['new'] ? $txt['new_posts'] : $txt['old_posts']) . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')">' . $child['name'] . ($child['new'] ? '</a> <a href="' . $scripturl . '?action=unread;board=' . $child['id'] . '" title="' . $txt['new_posts'] . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')"><img src="' . $settings['lang_images_url'] . '/new.gif" class="new_posts" alt="" />' : '') . '</a>';
							else
								$child['link'] = '<a href="' . $child['href'] . '" title="' . comma_format($child['posts']) . ' ' . $txt['redirects'] . '">' . $child['name'] . '</a>';

							// Has it posts awaiting approval?
							if ($child['can_approve_posts'] && ($child['unapproved_posts'] || $child['unapproved_topics']))
								$child['link'] .= ' <a href="' . $scripturl . '?action=moderate;area=postmod;sa=' . ($child['unapproved_topics'] > 0 ? 'topics' : 'posts') . ';brd=' . $child['id'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '" title="' . sprintf($txt['unapproved_posts'], $child['unapproved_topics'], $child['unapproved_posts']) . '" class="moderation_link">(!)</a>';

							$children[] = $child['new'] ? '<strong>' . $child['link'] . '</strong>' : $child['link'];
						}
						echo '
					 <div class="children-content" id="board_', $board['id'], '_children" style="display:none">
 							<strong>', $txt['parent_boards'], '</strong>: ', implode(', ', $children), '
 					 </div> ';
 					echo '					 
					</li>';

					}
				}

			echo '
				</ul>';
		}
		echo'</div>';
	 
	}
