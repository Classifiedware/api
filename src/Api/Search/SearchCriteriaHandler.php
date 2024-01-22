<?php declare(strict_types=1);

namespace App\Api\Search;

use Psr\Log\LoggerInterface;

class SearchCriteriaHandler
{
    private array $allowedPropertyGroupOptionIds = [];

    private array $excludedPropertyGroupOptionIds = [];

    /**
     * @param iterable<SearchCriteriaHandlerInterface> $handlers
     */
    public function __construct(
       private readonly iterable $handlers,
       private readonly LoggerInterface $logger
    ) {
    }

    public function handle(ClassifiedSearchDto $searchDto): void
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($searchDto)) {
                $this->logger->debug(sprintf('Handling search for %s', get_class($handler)));

                $allowedPropertyGroupOptionIds = $handler->getAllowedPropertyGroupOptionIds($searchDto);
                foreach ($allowedPropertyGroupOptionIds as $allowedPropertyGroupOptionId) {
                    $this->allowedPropertyGroupOptionIds[] = $allowedPropertyGroupOptionId;
                }

                $excludedPropertyGroupOptionIds = $handler->getExcludedPropertyGroupOptionIds($searchDto);
                foreach ($excludedPropertyGroupOptionIds as $excludedPropertyGroupOptionId) {
                    $this->excludedPropertyGroupOptionIds[] = $excludedPropertyGroupOptionId;
                }
            }
        }
    }

    public function getAllowedPropertyGroupOptionIds(): array
    {
        return $this->allowedPropertyGroupOptionIds;
    }

    public function getExcludedPropertyGroupOptionIds(): array
    {
        return $this->excludedPropertyGroupOptionIds;
    }
}