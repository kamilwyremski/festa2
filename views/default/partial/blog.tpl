

{% if blogs %}
	<ul class="list-unstyled blogs_list">
		{% for blog in blogs %}
			<li class="media">
				<a href="{{ path('blog',blog.id,blog.slug) }}" class="mr-3">
					<img src="{% if blog.thumb %}{{ blog.thumb }}{% else %}{{ settings.base_url }}/views/{{ settings.template }}/images/no_image.png{% endif %}" alt="{{ blog.name }}" class="img-fluid img-thumbnail" onerror="this.src='{{ settings.base_url }}/views/{{ settings.template }}/images/no_image.png'">
				</a>
				<div class="media-body">
					<h5 class="mt-0 mb-2"><a href="{{ path('blog',blog.id,blog.slug) }}" class="main-color-2">{{ blog.name }}</a></h5>
					<p class="text-muted small mb-2">{{ blog.date|date("d-m-Y") }}</p>
					<p>{{ blog.lid }}</p>
				</div>
			</li>
		{% endfor %}
	</ul>
{% else %}
	<h3 class="text-danger">{{ 'Nothing found'|trans }}</h3>
{% endif %}


