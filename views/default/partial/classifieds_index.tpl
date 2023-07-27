
<div class="row">
{% for classified in classifieds %}
	<div class="col-lg-4 col-md-6 col-sm-12 text-center" itemscope itemtype="https://schema.org/Accommodation">
		<a href="{{ path('classified',classified.id,classified.slug) }}" title="{{ classified.name }}" itemprop="url" class="classifieds_index overflow_hidden {% if classified.promoted %}classifieds_index_promoted{% endif %}" >
			<img src="{% if classified.thumb %}upload/photos/{{ classified.thumb }}{% else %}{{ settings.base_url }}/views/{{ settings.template }}/images/no_image.png{% endif %}" alt="{{ classified.name }}" onerror="this.src='{{ settings.base_url }}/views/{{ settings.template }}/images/no_image.png'" itemprop="image" loading="lazy" width="500" height="300">
			<div class="classifieds_index_bottom">
				<h5 class="text"><strong itemprop="name">{{ classified.name }}</strong></h6>
				<h6 class="text">
					{% if classified.location_name or classified.category_name %}
						{{ classified.location_name }}
						{% if classified.location_name and classified.category_name %} | {% endif %}
						{{ classified.category_name }}
					{% else %}
						&nbsp;
					{% endif %}
				</h6>
			</div>
		</a>
	</div>
{% endfor %}
</div>
