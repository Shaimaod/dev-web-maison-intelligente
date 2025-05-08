@foreach($activities as $activity)
    <tr data-id="{{ $activity->id }}" class="activity-item">
        <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
        <td>
            @switch($activity->action_type)
                @case('login')
                    <span class="activity-badge badge-login">
                        <i class="fas fa-sign-in-alt me-1"></i>Connexion
                    </span>
                    @break
                @case('logout')
                    <span class="activity-badge badge-logout">
                        <i class="fas fa-sign-out-alt me-1"></i>Déconnexion
                    </span>
                    @break
                @case('search')
                @case('object_search')
                    <span class="activity-badge badge-search">
                        <i class="fas fa-search me-1"></i>Recherche
                    </span>
                    @break
                @case('profile_update')
                    <span class="activity-badge badge-profile">
                        <i class="fas fa-user-edit me-1"></i>Profil
                    </span>
                    @break
                @case('object_add')
                @case('object_update')
                @case('object_delete')
                    <span class="activity-badge badge-object">
                        <i class="fas fa-plug me-1"></i>Objet
                    </span>
                    @break
                @case('deletion_request')
                    <span class="activity-badge badge-object">
                        <i class="fas fa-trash-alt me-1"></i>Demande de suppression
                    </span>
                    @break
                @default
                    <span class="activity-badge badge-default">
                        <i class="fas fa-info-circle me-1"></i>{{ ucfirst($activity->action_type) }}
                    </span>
            @endswitch
        </td>
        <td>{{ $activity->description }}</td>
        <td>
            @if($activity->details)
                <button type="button" class="details-button" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $activity->id }}">
                    <i class="fas fa-info-circle me-1"></i>Voir les détails
                </button>

                <!-- Modal -->
                <div class="modal fade" id="detailsModal{{ $activity->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Détails de l'action</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if(is_array($activity->details) || is_object($activity->details))
                                    <ul class="details-list">
                                        @foreach($activity->details as $key => $value)
                                            @if($key === 'changes' && is_array($value))
                                                <li>
                                                    <strong>Modifications:</strong>
                                                    <ul>
                                                        @foreach($value as $fieldKey => $change)
                                                            @if(is_array($change) && isset($change['from']) && isset($change['to']))
                                                                <li>{{ ucfirst($fieldKey) }}: 
                                                                    {{ is_array($change['from']) ? json_encode($change['from']) : $change['from'] }} 
                                                                    → 
                                                                    {{ is_array($change['to']) ? json_encode($change['to']) : $change['to'] }}
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @elseif(!is_array($value))
                                                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                                            @elseif(is_array($value))
                                                <li>
                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                    <ul>
                                                        @php
                                                            $changes = is_string($value) ? json_decode($value, true) : $value;
                                                        @endphp
                                                        
                                                        @if(is_array($changes))
                                                            @foreach($changes as $fieldKey => $change)
                                                                @if(is_array($change) && isset($change['from']) && isset($change['to']))
                                                                    <li>{{ ucfirst($fieldKey) }}: 
                                                                        {{ is_array($change['from']) ? json_encode($change['from']) : $change['from'] }} 
                                                                        → 
                                                                        {{ is_array($change['to']) ? json_encode($change['to']) : $change['to'] }}
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <li>Aucune modification détaillée disponible</li>
                                                        @endif
                                                    </ul>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">Aucun détail disponible pour cette activité.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <span class="text-muted">Aucun détail</span>
            @endif
        </td>
    </tr>
@endforeach
